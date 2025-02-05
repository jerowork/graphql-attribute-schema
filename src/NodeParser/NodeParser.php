<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Generator;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use ReflectionClass;
use ReflectionMethod;

/**
 * @internal
 */
interface NodeParser
{
    /**
     * @param class-string $attribute
     *
     * @throws ParseException
     *
     * @return Generator<Node>
     */
    public function parse(string $attribute, ReflectionClass $class, ?ReflectionMethod $method): Generator;
}
