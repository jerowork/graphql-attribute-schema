<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Exception;

final class ParseException extends Exception
{
    public static function invalidReturnType(string $class, string $method): self
    {
        return new self(sprintf('Invalid return type for Mutation %s:%s', $class, $method));
    }

    public static function noReturnType(string $class, string $method): self
    {
        return new self(sprintf('No return type for Mutation %s:%s', $class, $method));
    }

    public static function voidReturnType(string $class, string $method): self
    {
        return new self(sprintf('Void return type for Mutation %s:%s', $class, $method));
    }

    public static function invalidParameterType(string $method, string $parameter): self
    {
        return new self(sprintf('Invalid parameter type for method %s:%s', $method, $parameter));
    }

    public static function invalidPropertyType(string $class, string $property): self
    {
        return new self(sprintf('Invalid property type for class %s:%s', $class, $property));
    }

    public static function invalidNameForResolver(string $name): self
    {
        return new self(sprintf('Invalid characters in resolver name: %s', $name));
    }

    public static function invalidNameForType(string $name): self
    {
        return new self(sprintf('Invalid characters in type name: %s', $name));
    }

    public static function missingMethodInClass(string $class): self
    {
        return new self(sprintf('Missing method in class: %s', $class));
    }

    public static function tooManyMethodsInClass(string $class): self
    {
        return new self(sprintf('Too many methods in class: %s', $class));
    }

    public static function notAnEnumClass(string $class): self
    {
        return new self(sprintf('Class %s is not an enum class', $class));
    }

    public static function notABackedEnumClass(string $class): self
    {
        return new self(sprintf('Enum %s is not a BackedEnum', $class));
    }

    public static function missingAttributeOnClass(string $class, string $attribute): self
    {
        return new self(sprintf('Missing attribute %s on class %s', $attribute, $class));
    }
}
