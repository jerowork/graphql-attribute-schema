<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver;

use Exception;

final class ResolveException extends Exception
{
    public static function logicError(string $error): self
    {
        return new self(sprintf('Logic error: %s', $error));
    }
}
