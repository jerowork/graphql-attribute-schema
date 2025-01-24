<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use ReflectionClass;
use ReflectionMethod;
use Generator;

/**
 * @internal
 */
final readonly class ChainedNodeParser implements NodeParser
{
    /**
     * @param iterable<NodeParser> $nodeParsers
     */
    public function __construct(
        private iterable $nodeParsers,
    ) {}

    public function parse(string $attribute, ReflectionClass $class, ?ReflectionMethod $method): Generator
    {
        foreach ($this->nodeParsers as $nodeParser) {
            yield from $nodeParser->parse($attribute, $class, $method);
        }
    }
}
