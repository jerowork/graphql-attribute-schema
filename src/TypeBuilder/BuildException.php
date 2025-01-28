<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use Exception;

/**
 * @internal
 */
final class BuildException extends Exception
{
    /**
     * @param class-string $type
     */
    public static function invalidTypeForField(string $field, string $type): self
    {
        return new self(sprintf('Invalid type %s for field %s', $type, $field));
    }

    /**
     * @param class-string $type
     */
    public static function implementedTypeIsNotAnInterface(string $type): self
    {
        return new self(sprintf('Implemented type %s is not an interface', $type));
    }

    public static function logicError(string $error): self
    {
        return new self(sprintf('Logic error: %s', $error));
    }
}
