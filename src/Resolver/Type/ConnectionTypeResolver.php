<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\InterfaceTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Connection\EdgeTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Connection\PageInfoTypeResolver;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use LogicException;
use Override;

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
        private readonly PageInfoTypeResolver $pageInfoTypeResolver,
        private readonly EdgeTypeResolver $edgeTypeResolver,
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

        $connectionName = sprintf('%sConnection', $node->name);

        if ($this->builtTypesRegistry->hasType($connectionName)) {
            return $this->builtTypesRegistry->getType($connectionName);
        }

        /** @var Type&NullableType $pageInfo */
        $pageInfo = $this->pageInfoTypeResolver->createPageInfo();

        $connection = new ObjectType([
            'name' => $connectionName,
            'fields' => [
                [
                    'name' => 'edges',
                    'type' => Type::nonNull(Type::listOf(
                        $this->edgeTypeResolver->createEdgeType($reference, $node, $this->getTypeResolverSelector()),
                    )),
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
}
