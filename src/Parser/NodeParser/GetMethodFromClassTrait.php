<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use ReflectionClass;
use ReflectionMethod;

trait GetMethodFromClassTrait
{
    private const array RESERVED_METHOD_NAMES = ['__construct'];

    /**
     * @param ReflectionClass<object> $class
     *
     * @throws ParseException
     */
    public function getMethodFromClass(ReflectionClass $class): ReflectionMethod
    {
        $methods = array_filter(
            $class->getMethods(),
            fn(ReflectionMethod $method): bool => !in_array($method->getName(), self::RESERVED_METHOD_NAMES, true) && $method->isPublic(),
        );

        if ($methods === []) {
            throw ParseException::missingMethodInClass($class->getName());
        }

        if (count($methods) !== 1) {
            throw ParseException::tooManyMethodsInClass($class->getName());
        }

        return array_pop($methods);
    }
}
