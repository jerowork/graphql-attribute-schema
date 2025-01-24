<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Method;

use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\Node\Method\QueryNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetTypeReferenceTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\NodeParser\RetrieveNameForFieldTrait;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use ReflectionClass;
use Override;
use ReflectionMethod;
use ReflectionNamedType;

final readonly class QueryMethodNodeParser implements MethodNodeParser
{
    use RetrieveNameForFieldTrait;
    use GetTypeReferenceTrait;
    use GetAttributeTrait;

    public function __construct(
        private MethodArgumentNodesParser $methodArgumentNodesParser,
    ) {}

    #[Override]
    public function supports(string $attribute): bool
    {
        return $attribute === Query::class;
    }

    #[Override]
    public function parse(ReflectionClass $class, ReflectionMethod $method): Node
    {
        $attribute = $this->getAttribute($method, Query::class);
        $returnType = $method->getReturnType();

        $reference = $this->getTypeReference($returnType, $attribute);

        if ($reference === null) {
            throw ParseException::invalidReturnType($class->getName(), $method->getName());
        }

        // When reference is ConnectionType, the query needs to have Connection as return type
        if ($reference instanceof ConnectionTypeReference) {
            if (!$returnType instanceof ReflectionNamedType || $returnType->getName() !== Connection::class) {
                throw ParseException::invalidConnectionReturnType($class->getName(), $method->getName());
            }
        }

        /** @var list<ArgNode> $argumentNodes */
        $argumentNodes = $this->methodArgumentNodesParser->parse($method, false);

        return new QueryNode(
            $class->getName(),
            $this->retrieveNameForField($method, $attribute),
            $attribute->description,
            $argumentNodes,
            $reference,
            $method->getName(),
            $attribute->deprecationReason,
        );
    }
}
