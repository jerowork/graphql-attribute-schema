<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use LogicException;
use Override;

/**
 * @internal
 */
final class ObjectTypeResolver implements TypeResolver
{
    use TypeResolverSelectorAwareTrait;
    use GetNodeFromReferenceTrait;

    public function __construct(
        private readonly AstContainer $astContainer,
        private readonly FieldResolver $fieldResolver,
    ) {}

    #[Override]
    public function supports(TypeReference $reference): bool
    {
        if (!$reference instanceof ObjectTypeReference) {
            return false;
        }

        $node = $this->astContainer->getAst()->getNodeByClassName($reference->className);

        return $node instanceof TypeNode && !$node->isInterface;
    }

    #[Override]
    public function createType(TypeReference $reference): ObjectType
    {
        $node = $this->getNodeFromReference($reference, $this->astContainer->getAst(), TypeNode::class);

        $objectTypeConfig = [
            'name' => $node->name,
            'description' => $node->description,
            'fields' => $this->fieldResolver->getFields(
                $this->getCombinedFieldNodesFromInterfaceAndNode($node),
                $this->getTypeResolverSelector(),
            ),
        ];

        if ($node->implementsInterfaces !== []) {
            $objectTypeConfig['interfaces'] = $this->getInterfaces($node);
        }

        return new ObjectType($objectTypeConfig);
    }

    #[Override]
    public function resolve(TypeReference $reference, Closure $callback): mixed
    {
        return $callback();
    }

    #[Override]
    public function abstract(ArgumentNode|FieldNode $node, array $args): mixed
    {
        throw new LogicException('ObjectType does not need to abstract');
    }

    /**
     * @return list<FieldNode>
     */
    private function getCombinedFieldNodesFromInterfaceAndNode(TypeNode $node): array
    {
        $fields = [];
        $inMemoryFieldNames = [];

        // Add interface fields if present
        foreach ($node->implementsInterfaces as $interfaceClass) {
            /** @var null|TypeNode $interfaceTypeNode */
            $interfaceTypeNode = $this->astContainer->getAst()->getNodeByClassName($interfaceClass);

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
     * @return list<InterfaceType>
     */
    private function getInterfaces(TypeNode $node): array
    {
        $interfaces = [];

        foreach ($node->implementsInterfaces as $interfaceClass) {
            $interfaceTypeNode = $this->astContainer->getAst()->getNodeByClassName($interfaceClass);

            if ($interfaceTypeNode === null) {
                continue;
            }

            if (!$interfaceTypeNode instanceof TypeNode) {
                throw new LogicException(sprintf('Node is not a TypeNode: %s', $interfaceClass));
            }

            if (!$interfaceTypeNode->isInterface) {
                throw new LogicException('Interface type is not an interface');
            }

            $reference = ObjectTypeReference::create($interfaceClass);
            $interface = $this->getTypeResolverSelector()->getResolver($reference)->createType($reference);

            if ($interface instanceof NonNull) {
                $interface = $interface->getWrappedType();
            }

            if ($interface instanceof ListOfType) {
                $interface = $interface->getWrappedType();

                if ($interface instanceof NonNull) {
                    $interface = $interface->getWrappedType();
                }
            }

            /** @var InterfaceType $interface */
            $interfaces[] = $interface;
        }

        return $interfaces;
    }
}
