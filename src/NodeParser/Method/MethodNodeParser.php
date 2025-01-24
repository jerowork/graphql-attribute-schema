<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Method;

use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use ReflectionClass;
use ReflectionMethod;

interface MethodNodeParser
{
    /**
     * @param class-string $attribute
     */
    public function supports(string $attribute): bool;

    /**
     * @throws ParseException
     */
    public function parse(ReflectionClass $class, ReflectionMethod $method): Node;
}
