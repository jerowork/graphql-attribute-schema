<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use Exception;
use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\InterfaceTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use LogicException;
use Override;
use Psr\Container\ContainerInterface;

/**
 * @internal
 */
final class ConnectionTypeResolver implements TypeResolver
{
    use TypeResolverSelectorAwareTrait;
    use GetNodeFromReferenceTrait;

    public function __construct(
        private readonly AstContainer $astContainer,
        private readonly BuiltTypesRegistry $builtTypesRegistry,
        private readonly ContainerInterface $container,
        private readonly FieldResolver $fieldResolver,
    ) {}

    #[Override]
    public function supports(TypeReference $reference): bool
    {
        return $reference instanceof ConnectionTypeReference;
    }

    #[Override]
    public function createType(TypeReference $reference): Type
    {
        try {
            $node = $this->getNodeFromReference($reference, $this->astContainer->getAst(), TypeNode::class);
        } catch (LogicException) {
            $node = $this->getNodeFromReference($reference, $this->astContainer->getAst(), InterfaceTypeNode::class);
        }

        if (!$reference instanceof ConnectionTypeReference) {
            throw new LogicException('Reference is not a ConnectionTypeReference');
        }

        return $this->createConnectionType($node, $this->createEdgeType($reference, $node), $this->createPageInfo());
    }

    #[Override]
    public function resolve(TypeReference $reference, Closure $callback): mixed
    {
        return $callback($reference);
    }

    #[Override]
    public function abstract(ArgumentNode|FieldNode $node, array $args): mixed
    {
        throw new LogicException('ConnectionType does not need to abstract');
    }

    private function createEdgeType(
        ConnectionTypeReference $reference,
        InterfaceTypeNode|TypeNode $node,
    ): Type {
        $edgeName = sprintf('%sEdge', $node->name);

        $cursorNode = $node->cursorNode;

        if ($cursorNode !== null && $cursorNode->reference instanceof ObjectTypeReference) {
            $cursorNodeType = $this->astContainer->getAst()->getNodeByClassName($cursorNode->reference->className);

            if (!$cursorNodeType instanceof ScalarNode) {
                throw new Exception(sprintf('Invalid object type cursor connection edge type: %s (must be a CustomScalar)', $node->name));
            }
        }

        if ($this->builtTypesRegistry->hasType($edgeName)) {
            return $this->builtTypesRegistry->getType($edgeName);
        }

        $edge = Type::nonNull(new ObjectType([
            'name' => $edgeName,
            'fields' => [
                [
                    'name' => 'node',
                    'type' => $this->getTypeResolverSelector()
                        ->getResolver(ObjectTypeReference::create($reference->className))
                        ->createType(ObjectTypeReference::create($reference->className)),
                    'resolve' => fn($objectValue) => $objectValue,
                ],
                [
                    'name' => 'cursor',
                    'type' => $cursorNode?->reference->isValueNullable() !== false ? Type::string() : Type::nonNull(Type::string()),
                    'resolve' => $cursorNode !== null ? $this->resolveCursor($node, $cursorNode) : fn() => null,
                ],
            ],
        ]));

        $this->builtTypesRegistry->addType($edgeName, $edge);

        return $edge;
    }

    private function resolveCursor(InterfaceTypeNode|TypeNode $node, CursorNode $cursorNode): callable
    {
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
            return $this->fieldResolver->resolveField($fieldNode, $this->getTypeResolverSelector());
        }

        // Field is not matched
        if ($cursorNode->fieldType === FieldNodeType::Property) {
            return fn($objectValue) => $objectValue->{$cursorNode->propertyName};
        }

        return fn($objectValue) => $objectValue->{$cursorNode->methodName}();
    }

    private function createPageInfo(): Type
    {
        $pageInfoName = 'PageInfo';

        if ($this->builtTypesRegistry->hasType($pageInfoName)) {
            return $this->builtTypesRegistry->getType($pageInfoName);
        }

        $pageInfo = new ObjectType([
            'name' => $pageInfoName,
            'fields' => [
                [
                    'name' => 'hasPreviousPage',
                    'type' => Type::nonNull(Type::boolean()),
                ],
                [
                    'name' => 'hasNextPage',
                    'type' => Type::nonNull(Type::boolean()),
                ],
                [
                    'name' => 'startCursor',
                    'type' => Type::string(),
                ],
                [
                    'name' => 'endCursor',
                    'type' => Type::string(),
                ],
            ],
        ]);

        $this->builtTypesRegistry->addType($pageInfoName, $pageInfo);

        return $pageInfo;
    }

    private function createConnectionType(InterfaceTypeNode|TypeNode $node, Type $edge, Type $pageInfo): Type
    {
        $connectionName = sprintf('%sConnection', $node->name);

        if ($this->builtTypesRegistry->hasType($connectionName)) {
            return $this->builtTypesRegistry->getType($connectionName);
        }

        /** @var Type&NullableType $pageInfo */
        $connection = new ObjectType([
            'name' => $connectionName,
            'fields' => [
                [
                    'name' => 'edges',
                    'type' => Type::nonNull(Type::listOf($edge)),
                    'resolve' => fn(Connection $connection) => $connection,
                ],
                [
                    'name' => 'pageInfo',
                    'type' => Type::nonNull($pageInfo),
                    'resolve' => fn(Connection $connection) => [
                        'hasPreviousPage' => $connection->hasPreviousPage,
                        'hasNextPage' => $connection->hasNextPage,
                        'startCursor' => $connection->startCursor,
                        'endCursor' => $connection->endCursor,
                    ],
                ],
            ],
        ]);

        $this->builtTypesRegistry->addType($connectionName, $connection);

        return $connection;
    }
}
