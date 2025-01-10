<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Method;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetTypeTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\RetrieveNameForResolverTrait;
use ReflectionClass;
use Override;
use ReflectionMethod;

final readonly class MutationMethodNodeParser implements MethodNodeParser
{
    use RetrieveNameForResolverTrait;
    use GetTypeTrait;
    use GetAttributeTrait;

    public function __construct(
        private MethodArgumentNodesParser $methodArgumentNodesParser,
    ) {}

    #[Override]
    public function supports(string $attribute): bool
    {
        return $attribute === Mutation::class;
    }

    #[Override]
    public function parse(ReflectionClass $class, ReflectionMethod $method): Node
    {
        $attribute = $this->getAttribute($method, Mutation::class);

        $type = $this->getType($method->getReturnType(), $attribute);

        if ($type === null) {
            throw ParseException::invalidReturnType($class->getName(), $method->getName());
        }

        /** @var list<ArgNode> $argumentNodes */
        $argumentNodes = $this->methodArgumentNodesParser->parse($method, false);

        return new MutationNode(
            $class->getName(),
            $this->retrieveNameForResolver($method, $attribute),
            $attribute->getDescription(),
            $argumentNodes,
            $type,
            $method->getName(),
            $attribute->deprecationReason,
        );
    }
}
