<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Jerowork\GraphqlAttributeSchema\Node\Node;
use ReflectionClass;
use ReflectionMethod;

interface NodeParser
{
    /**
     * @param class-string $attribute
     */
    public function supports(string $attribute): bool;

    /**
     * @throws ParseException
     */
    public function parse(ReflectionClass $class, ?ReflectionMethod $method): Node;
}
