<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\TypedAttribute;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use ReflectionNamedType;
use ReflectionType;

trait GetTypeTrait
{
    public function getType(?ReflectionType $type, ?TypedAttribute $attribute): ?Type
    {
        // Retrieve from attribute if set
        if ($attribute?->getType() !== null) {
            $attributeType = $attribute->getType();

            if ($attributeType instanceof ScalarType) {
                return Type::createScalar($attributeType->value);
            }

            return Type::createObject($attributeType);
        }

        // Retrieve from class
        if (!$type instanceof ReflectionNamedType) {
            return null;
        }

        return $type->isBuiltin() ? Type::createScalar($type->getName()) : Type::createObject($type->getName());
    }
}
