<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object;

use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\GetConnectionArgsTrait;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use Override;

/**
 * @phpstan-type TypeFieldPayload array{
 *     name: string,
 *     type: Type,
 *     args: list<array{
 *         name: string,
 *         type: Type,
 *         description: null|string
 *     }>,
 *     resolve: callable
 * }
 *
 * @implements ObjectTypeBuilder<TypeNode>
 *
 * @internal
 */
final readonly class TypeObjectTypeBuilder implements ObjectTypeBuilder
{
    use BuildArgsTrait;
    use GetConnectionArgsTrait;

    public function __construct(
        private BuiltTypesRegistry $builtTypesRegistry,
        private FieldResolver $typeResolver,
    ) {}

    #[Override]
    public function supports(Node $node): bool
    {
        return $node instanceof TypeNode && !$node->isInterface;
    }

    #[Override]
    public function build(Node $node, ExecutingTypeBuilder $typeBuilder, Ast $ast): Type
    {
        $config = [
            'name' => $node->name,
            'description' => $node->description,
            'fields' => $this->buildFields(
                $this->getNodeFields($node, $ast),
                $typeBuilder,
                $ast,
            ),
        ];

        if ($node->implementsInterfaces !== []) {
            $config['interfaces'] = $this->getInterfaces($node, $typeBuilder, $ast);
        }

        return new ObjectType($config);
    }

    /**
     * @return list<FieldNode>
     */
    private function getNodeFields(TypeNode $node, Ast $ast): array
    {
        $fields = [];
        $inMemoryFieldNames = [];

        // Add interface fields if present
        foreach ($node->implementsInterfaces as $interfaceClass) {
            /** @var TypeNode|null $interfaceTypeNode */
            $interfaceTypeNode = $ast->getNodeByClassName($interfaceClass);

            if ($interfaceTypeNode === null) {
                continue;
            }

            $inMemoryFieldNames = [...$inMemoryFieldNames, ...array_map(
                fn($fieldNode) => $fieldNode->name,
                $interfaceTypeNode->fieldNodes,
            )];

            $fields = [...$fields, ...$interfaceTypeNode->fieldNodes];
        }

        // Add main node fields
        foreach ($node->fieldNodes as $fieldNode) {
            if (in_array($fieldNode->name, $inMemoryFieldNames, true)) {
                continue;
            }

            $fields[] = $fieldNode;
        }

        return $fields;
    }

    /**
     * @throws BuildException
     *
     * @return list<InterfaceType>
     */
    private function getInterfaces(TypeNode $node, ExecutingTypeBuilder $typeBuilder, Ast $ast): array
    {
        $interfaces = [];

        foreach ($node->implementsInterfaces as $interfaceClass) {
            $interfaceTypeNode = $ast->getNodeByClassName($interfaceClass);

            if ($interfaceTypeNode === null) {
                continue;
            }

            if (!$interfaceTypeNode instanceof TypeNode) {
                BuildException::logicError(sprintf(
                    'Implemented interface %s is not a TypeNode',
                    $interfaceTypeNode->getClassName(),
                ));
            }

            /** @var TypeNode $interfaceTypeNode */
            if (!$interfaceTypeNode->isInterface) {
                BuildException::implementedTypeIsNotAnInterface($interfaceTypeNode->getClassName());
            }

            if ($this->builtTypesRegistry->hasType($interfaceClass)) {
                $interfaces[] = $this->builtTypesRegistry->getType($interfaceClass);

                continue;
            }

            $interfaceFields = $this->buildFields($interfaceTypeNode->fieldNodes, $typeBuilder, $ast);

            // @phpstan-ignore-next-line
            $interface = new InterfaceType([
                'name' => $interfaceTypeNode->name,
                'description' => $interfaceTypeNode->description,
                'fields' => $interfaceFields,
                'resolveType' => fn(object $objectValue) => $this->builtTypesRegistry->getType($objectValue::class),
            ]);

            $this->builtTypesRegistry->addType($interfaceTypeNode->getClassName(), $interface);

            $interfaces[] = $interface;
        }

        /** @var list<InterfaceType> $interfaces */
        return $interfaces;
    }

    /**
     * @param list<FieldNode> $fieldNodes
     *
     * @return list<TypeFieldPayload>
     */
    private function buildFields(array $fieldNodes, ExecutingTypeBuilder $typeBuilder, Ast $ast): array
    {
        $fields = [];

        foreach ($fieldNodes as $fieldNode) {
            $field = [
                'name' => $fieldNode->name,
                'type' => $typeBuilder->build($fieldNode->reference, $ast),
                'description' => $fieldNode->description,
                'args' => [
                    ...$this->buildArgs($fieldNode, $typeBuilder, $ast),
                    ...$this->getConnectionArgs($fieldNode->reference),
                ],
                'resolve' => $this->typeResolver->resolve($fieldNode, $ast),
            ];

            if ($fieldNode->deprecationReason !== null) {
                $field['deprecationReason'] = $fieldNode->deprecationReason;
            }

            $fields[] = $field;
        }

        return $fields;
    }
}
