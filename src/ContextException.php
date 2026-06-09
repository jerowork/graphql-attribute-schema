<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use RuntimeException;

final class ContextException extends RuntimeException
{
    public static function contextNotAvailable(string $fqcn): self
    {
        return new self(sprintf(
            'Cannot map context parameter of type %s: the GraphQL context value is not an instance of %s. '
            . 'Provide a %s as the context value on your GraphQL server.',
            $fqcn,
            Context::class,
            Context::class,
        ));
    }

    public static function contextNotInjectable(): self
    {
        return new self(sprintf(
            'Cannot inject the %s parameter: the GraphQL context value is not an instance of %s. '
            . 'Provide a %s as the context value on your GraphQL server.',
            Context::class,
            Context::class,
            Context::class,
        ));
    }

    public static function missingObject(string $fqcn): self
    {
        return new self(sprintf('No object of type %s found in context', $fqcn));
    }

    public static function unexpectedObjectType(string $fqcn): self
    {
        return new self(sprintf('Object in context is not of expected type %s', $fqcn));
    }
}
