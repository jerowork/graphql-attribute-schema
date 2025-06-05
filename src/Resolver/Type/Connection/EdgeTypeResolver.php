<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type\Connection;

use Closure;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\InterfaceTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Field\FieldResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelector;
use LogicException;

/**
 * @internal
 */
final readonly class EdgeTypeResolver
{
    public function __construct(
        private AstContainer $astContainer,
        private BuiltTypesRegistry $builtTypesRegistry,
        private FieldResolver $fieldResolver,
    ) {}

    public function createEdgeType(
        ConnectionTypeReference $reference,
        InterfaceTypeNode|TypeNode $node,
        TypeResolverSelector $typeResolverSelector,
    ): Type {
        $edgeName = sprintf('%sEdge', $node->name);

        $cursorNode = $node->cursorNode;

        if ($cursorNode !== null && $cursorNode->reference instanceof ObjectTypeReference) {
            $cursorNodeType = $this->astContainer->getAst()->getNodeByClassName($cursorNode->reference->className);

            if (!$cursorNodeType instanceof ScalarNode) {
                throw new LogicException(sprintf(
                    'Invalid object type cursor connection edge type: %s (must be a CustomScalar)',
                    $node->name,
                ));
            }
        }

        if ($this->builtTypesRegistry->hasType($edgeName)) {
            return $this->builtTypesRegistry->getType($edgeName);
        }

        $edge = Type::nonNull(new ObjectType([
            'name' => $edgeName,
            'fields' => fn() => [
                [
                    'name' => 'node',
                    'type' => fn() => $typeResolverSelector
                        ->getResolver(ObjectTypeReference::create($reference->className))
                        ->createType(ObjectTypeReference::create($reference->className)),
                    'resolve' => fn($objectValue) => $objectValue,
                ],
                [
                    'name' => 'cursor',
                    'type' => fn() => $cursorNode?->reference->isValueNullable() !== false ? Type::string() : Type::nonNull(Type::string()),
                    'resolve' => $cursorNode !== null ? $this->resolveCursor($node, $cursorNode, $typeResolverSelector) : fn() => null,
                ],
            ],
        ]));

        $this->builtTypesRegistry->addType($edgeName, $edge);

        return $edge;
    }

    private function resolveCursor(
        InterfaceTypeNode|TypeNode $node,
        CursorNode $cursorNode,
        TypeResolverSelector $typeResolverSelector,
    ): Closure {
        foreach ($node->fieldNodes as $fieldNode) {
            if (
                $fieldNode->fieldType !== $cursorNode->fieldType
                || $fieldNode->methodName !== $cursorNode->methodName
                || $fieldNode->propertyName !== $cursorNode->propertyName
            ) {
                continue;
            }

            foreach ($fieldNode->argumentNodes as $argumentNode) {
                if ($argumentNode instanceof ArgNode) {
                    throw new LogicException(sprintf(
                        'Cannot use a method with #[Arg] arguments as cursor field, cursor: %s',
                        $cursorNode->propertyName ?? $cursorNode->methodName,
                    ));
                }
            }

            // Field is matched
            return $this->fieldResolver->resolveField($fieldNode, $typeResolverSelector);
        }

        // Field is not matched
        if ($cursorNode->fieldType === FieldNodeType::Property) {
            return fn($objectValue) => $objectValue->{$cursorNode->propertyName};
        }

        return fn($objectValue) => $objectValue->{$cursorNode->methodName}();
    }
}
