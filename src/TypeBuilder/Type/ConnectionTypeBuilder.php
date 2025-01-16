<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;

/**
 * @implements TypeBuilder<ConnectionTypeReference>
 */
final readonly class ConnectionTypeBuilder implements TypeBuilder
{
    public function __construct(
        private BuiltTypesRegistry $builtTypesRegistry,
        private FieldResolver $fieldResolver,
    ) {}

    public function supports(TypeReference $reference): bool
    {
        return $reference instanceof ConnectionTypeReference;
    }

    public function build(TypeReference $reference, ExecutingTypeBuilder $typeBuilder, Ast $ast): Type
    {
        $node = $ast->getNodeByClassName($reference->className);

        if ($node === null) {
            throw BuildException::logicError(sprintf('No node found for connection: %s', $reference->className));
        }

        if (!$node instanceof TypeNode) {
            throw BuildException::logicError(sprintf('Invalid edge node for connection: %s', $reference->className));
        }

        $edge = $this->createEdgeType($reference, $node, $ast, $typeBuilder);
        $pageInfo = $this->createPageInfo();

        return $this->createConnectionType($node, $edge, $pageInfo);
    }

    /**
     * @throws BuildException
     */
    private function createEdgeType(
        ConnectionTypeReference $reference,
        TypeNode $node,
        Ast $ast,
        ExecutingTypeBuilder $typeBuilder,
    ): Type {
        $edgeName = sprintf('%sEdge', $node->name);

        // Get cursor data
        $cursorNode = $node->cursorNode;
        if ($cursorNode === null) {
            throw BuildException::logicError(sprintf('No cursor found for connection edge type: %s', $node->name));
        }

        if ($cursorNode->reference instanceof ObjectTypeReference) {
            $cursorNodeType = $ast->getNodeByClassName($cursorNode->reference->className);

            if (!$cursorNodeType instanceof CustomScalarNode) {
                throw BuildException::logicError(sprintf('Invalid object type cursor connection edge type: %s (must be a CustomScalar)', $node->name));
            }
        }

        try {
            return $this->builtTypesRegistry->getType($edgeName);
        } catch (BuildException) {
            $edge = Type::nonNull(new ObjectType([
                'name' => $edgeName,
                'fields' => [
                    [
                        'name' => 'node',
                        'type' => $typeBuilder->build(ObjectTypeReference::create($reference->className), $ast),
                        'resolve' => fn($objectValue) => $objectValue,
                    ],
                    [
                        'name' => 'cursor',
                        'type' => $cursorNode->reference->isValueNullable() ? Type::string() : Type::nonNull(Type::string()), // $typeBuilder->build($cursorNode->reference, $ast), // todo: maybe just string? as it mismatches with pageinfo obj
                        'resolve' => $this->resolveCursor($node, $cursorNode, $ast),
                    ],
                ],
            ]));

            $this->builtTypesRegistry->addType($edgeName, $edge);

            return $edge;
        }
    }

    /**
     * @throws BuildException
     */
    private function resolveCursor(TypeNode $node, CursorNode $cursorNode, Ast $ast): callable
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
                    throw BuildException::logicError(sprintf(
                        'Cannot use a method with #[Arg] arguments as cursor field, cursor: %s',
                        $cursorNode->propertyName ?? $cursorNode->methodName,
                    ));
                }
            }

            // Field is matched
            return $this->fieldResolver->resolve($fieldNode, $ast);
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

        try {
            return $this->builtTypesRegistry->getType($pageInfoName);
        } catch (BuildException) {
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
    }

    private function createConnectionType(TypeNode $node, Type $edge, Type $pageInfo): Type
    {
        $connectionName = sprintf('%sConnection', $node->name);

        try {
            return $this->builtTypesRegistry->getType($connectionName);
        } catch (BuildException) {
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
                        'type' => Type::nonNull($pageInfo), // @phpstan-ignore-line
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
}
