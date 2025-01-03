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

    /**
     * @param class-string $typeId
     */
    public static function nodeTypeIdNotInContainer(string $typeId): self
    {
        return new self(sprintf('Node type ID %s is not in a container (or not publicly accessible)', $typeId));
    }
}
