<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver;

use Exception;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

final class ResolveException extends Exception
{
    public static function logicError(string $error): self
    {
        return new self(sprintf('Logic error: %s', $error));
    }

    public static function nodeTypeNotInContainer(Type $type): self
    {
        return new self(sprintf('Node type ID %s is not in a container (or not publicly accessible)', $type->id));
    }
}
