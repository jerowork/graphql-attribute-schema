<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Exception;

final class ParseException extends Exception
{
    public static function invalidReturnType(string $class, string $method): self
    {
        return new self(sprintf('Invalid return type %s:%s', $class, $method));
    }

    public static function invalidConnectionReturnType(string $class, string $method): self
    {
        return new self(sprintf('Invalid return type for connection %s:%s', $class, $method));
    }

    public static function invalidParameterType(string $parameter): self
    {
        return new self(sprintf('Invalid arg parameter type for parameter %s', $parameter));
    }

    public static function invalidAutowiredParameterType(string $parameter): self
    {
        return new self(sprintf('Invalid autowired parameter type for parameter %s', $parameter));
    }

    public static function invalidPropertyType(string $class, string $property): self
    {
        return new self(sprintf('Invalid property type for class %s:%s', $class, $property));
    }

    public static function invalidConnectionPropertyType(string $class, string $property): self
    {
        return new self(sprintf('Invalid property type for connection for class %s:%s', $class, $property));
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

    public static function missingAttribute(string $reflector, string $attribute): self
    {
        return new self(sprintf('Missing attribute %s on %s', $attribute, $reflector));
    }

    public static function missingImplements(string $class, string $implements): self
    {
        return new self(sprintf('Class %s does not implement %s', $class, $implements));
    }

    public static function invalidListTypeConfiguration(string $type): self
    {
        return new self(sprintf('Invalid list type configuration, %s cannot be a list type', $type));
    }

    public static function multipleCursorsFound(string $class): self
    {
        return new self(sprintf('Multiple cursors found for class: %s', $class));
    }
}
