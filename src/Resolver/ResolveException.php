<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver;

use Exception;

/**
 * @internal
 */
final class ResolveException extends Exception
{
    public static function rootTypeNotInContainer(string $className): self
    {
        return new self(sprintf('Root type class not found or inaccessible in PSR-11 container: %s', $className));
    }
}
