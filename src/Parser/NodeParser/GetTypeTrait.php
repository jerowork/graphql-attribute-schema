<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use ReflectionNamedType;

trait GetTypeTrait
{
    public function getType(ReflectionNamedType $type): ?string
    {
        return $type->isBuiltin() ? $type->getName() : null;
    }
}
