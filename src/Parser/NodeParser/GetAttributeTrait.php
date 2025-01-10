<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\BaseAttribute;
use ReflectionClass;
use ReflectionMethod;

trait GetAttributeTrait
{
    /**
     * @template T of BaseAttribute
     *
     * @param ReflectionClass<object>|ReflectionMethod $reflector
     * @param class-string<T> $attributeName
     *
     * @throws ParseException
     *
     * @return T
     */
    public function getAttribute(ReflectionClass|ReflectionMethod $reflector, string $attributeName): BaseAttribute
    {
        $attributes = $reflector->getAttributes($attributeName);

        if ($attributes === []) {
            throw ParseException::missingAttribute($reflector->getName(), $attributeName);
        }

        return array_pop($attributes)->newInstance();
    }
}
