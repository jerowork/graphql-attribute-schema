<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

/**
 * @internal
 */
trait GetAttributeTrait
{
    /**
     * @template T of object
     *
     * @param class-string<T> $attributeName
     *
     * @throws ParseException
     *
     * @return T
     */
    public function getAttribute(ReflectionClass|ReflectionMethod|ReflectionParameter $reflector, string $attributeName): object
    {
        $attributes = $reflector->getAttributes($attributeName);

        if ($attributes === []) {
            throw ParseException::missingAttribute($reflector->getName(), $attributeName);
        }

        return array_pop($attributes)->newInstance();
    }
}
