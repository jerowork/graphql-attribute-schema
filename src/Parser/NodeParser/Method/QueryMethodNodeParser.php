<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Method;

use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\QueryNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetReferenceTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\RetrieveNameForFieldTrait;
use ReflectionClass;
use Override;
use ReflectionMethod;

final readonly class QueryMethodNodeParser implements MethodNodeParser
{
    use RetrieveNameForFieldTrait;
    use GetReferenceTrait;
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

        $reference = $this->getReference($method->getReturnType(), $attribute);

        if ($reference === null) {
            throw ParseException::invalidReturnType($class->getName(), $method->getName());
        }

        /** @var list<ArgNode> $argumentNodes */
        $argumentNodes = $this->methodArgumentNodesParser->parse($method, false);

        return new QueryNode(
            $class->getName(),
            $this->retrieveNameForField($method, $attribute),
            $attribute->getDescription(),
            $argumentNodes,
            $reference,
            $method->getName(),
            $attribute->deprecationReason,
        );
    }
}
