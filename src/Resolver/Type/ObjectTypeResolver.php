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
use Jerowork\GraphqlAttributeSchema\Node\InterfaceTypeNode;
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

        return $node instanceof TypeNode;
    }

    #[Override]
    public function createType(TypeReference $reference): ObjectType
    {
        $node = $this->getNodeFromReference($reference, $this->astContainer->getAst(), TypeNode::class);

        $objectTypeConfig = [
            'name' => $node->name,
            'description' => $node->description,
            'fields' => fn() => $this->fieldResolver->getFields(
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

        // Add interface fields if present
        foreach ($node->implementsInterfaces as $interfaceClass) {
            /** @var null|TypeNode $interfaceTypeNode */
            $interfaceTypeNode = $this->astContainer->getAst()->getNodeByClassName($interfaceClass);

            if ($interfaceTypeNode === null) {
                continue;
            }

            $fields = [...$fields, ...$interfaceTypeNode->fieldNodes];
        }

        // Add main node fields
        $fields = [...$fields, ...$node->fieldNodes];

        // Deduplicate fields (coming from multiple interfaces and main node)
        $inMemoryFieldNames = [];

        /** @var FieldNode[] $fields */
        foreach ($fields as $index => $field) {
            if (in_array($field->name, $inMemoryFieldNames, true)) {
                unset($fields[$index]);
            }

            $inMemoryFieldNames[] = $field->name;
        }

        return array_values($fields);
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

            if (!$interfaceTypeNode instanceof InterfaceTypeNode) {
                throw new LogicException(sprintf('Node is not an InterfaceTypeNode: %s', $interfaceClass));
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
