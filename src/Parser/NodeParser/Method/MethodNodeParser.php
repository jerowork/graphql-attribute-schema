<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Method;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use ReflectionClass;
use ReflectionMethod;

interface MethodNodeParser
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
    public function parse(ReflectionClass $class, ReflectionMethod $method): Node;
}
