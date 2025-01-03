<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\TypedAttribute;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use ReflectionNamedType;

trait GetTypeTrait
{
    public function getType(ReflectionNamedType $type, ?TypedAttribute $attribute): Type
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
        return $type->isBuiltin() ? Type::createScalar($type->getName()) : Type::createObject($type->getName());
    }
}
