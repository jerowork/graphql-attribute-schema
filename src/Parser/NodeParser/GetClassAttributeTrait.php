<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\GraphQLAttribute;
use ReflectionClass;

trait GetClassAttributeTrait
{
    /**
     * @template T of GraphQLAttribute
     *
     * @param ReflectionClass<object> $class
     * @param class-string<T> $attributeName
     *
     * @throws ParseException
     *
     * @return T
     */
    public function getClassAttribute(ReflectionClass $class, string $attributeName): GraphQLAttribute
    {
        $attributes = $class->getAttributes($attributeName);

        if ($attributes === []) {
            throw ParseException::missingAttributeOnClass($class->getName(), $attributeName);
        }

        return array_pop($attributes)->newInstance();
    }
}
