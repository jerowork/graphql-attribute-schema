<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use ReflectionClass;

interface NodeParser
{
    /**
     * @param class-string $attribute
     */
    public function supports(string $attribute): bool;

    /**
     * @param ReflectionClass<object> $class
     *
     * @throws ParseException
     */
    public function parse(ReflectionClass $class): Node;
}
