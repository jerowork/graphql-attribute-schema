<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\TypedAttribute;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use ReflectionNamedType;

trait GetTypeTrait
{
    private const array SCALAR_TYPES = ['string', 'int', 'float', 'bool'];

    public function getType(ReflectionNamedType $type, ?TypedAttribute $attribute): Type
    {
        // Retrieve from attribute if set
        if ($attribute?->getType() !== null) {
            return in_array($attribute->getType(), self::SCALAR_TYPES, true) ?
                Type::createScalar($attribute->getType()) :
                Type::createObject($attribute->getType());
        }

        // Retrieve from class
        if ($type->isBuiltin()) {
            return Type::createScalar($type->getName());
        }

        return Type::createObject($type->getName());
    }
}
