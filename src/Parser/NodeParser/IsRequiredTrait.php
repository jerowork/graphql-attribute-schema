<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\TypedAttribute;
use ReflectionNamedType;

trait IsRequiredTrait
{
    public function isRequired(ReflectionNamedType $type, ?TypedAttribute $attribute): bool
    {
        // Retrieve from attribute if set
        if ($attribute?->getType() !== null) {
            return $attribute->isRequired();
        }

        // Retrieve from class
        return !$type->allowsNull();
    }
}
