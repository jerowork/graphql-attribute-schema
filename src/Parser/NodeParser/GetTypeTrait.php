<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ObjectType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\Type as OptionType;
use Jerowork\GraphqlAttributeSchema\Attribute\TypedAttribute;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use ReflectionNamedType;
use ReflectionType;
use LogicException;

trait GetTypeTrait
{
    private const array ALLOWED_SCALAR_TYPES = ['float', 'string', 'int', 'bool'];

    public function getType(?ReflectionType $reflectionType, ?TypedAttribute $attribute): ?Type
    {
        // Retrieve from attribute if set
        if ($attribute?->getType() !== null) {
            $attributeType = $attribute->getType();

            if ($attributeType instanceof ListType) {
                if ($attributeType->type instanceof NullableType) {
                    return $this->getTypeFromAttribute($attributeType->type->type)
                        ->setList()
                        ->setNullableValue();
                }

                return $this->getTypeFromAttribute($attributeType->type)
                    ->setList();
            }

            if ($attributeType instanceof NullableType) {
                if ($attributeType->type instanceof ListType) {
                    if ($attributeType->type->type instanceof NullableType) {
                        return $this->getTypeFromAttribute($attributeType->type->type->type)
                            ->setList()
                            ->setNullableList()
                            ->setNullableValue();
                    }

                    return $this->getTypeFromAttribute($attributeType->type->type)
                        ->setList()
                        ->setNullableList();
                }

                return $this->getTypeFromAttribute($attributeType->type)
                    ->setNullableValue();
            }

            return $this->getTypeFromAttribute($attributeType);
        }

        // Retrieve from class
        if (!$reflectionType instanceof ReflectionNamedType) {
            return null;
        }

        if ($reflectionType->isBuiltin() && !in_array($reflectionType->getName(), self::ALLOWED_SCALAR_TYPES, true)) {
            return null;
        }

        if ($reflectionType->isBuiltin()) {
            $type = Type::createScalar($reflectionType->getName());
        } else {
            /** @var class-string $className */
            $className = $reflectionType->getName();

            $type = Type::createObject($className);
        }

        return $reflectionType->allowsNull() ? $type->setNullableValue() : $type;
    }

    private function getTypeFromAttribute(string|OptionType|ScalarType $type): Type
    {
        if ($type instanceof ScalarType) {
            return Type::createScalar($type->value);
        }

        if ($type instanceof ObjectType) {
            return Type::createObject($type->className);
        }

        if (is_string($type)) {
            /** @var class-string $type */
            return Type::createObject($type);
        }

        throw new LogicException('Failed to determine type from Attribute');
    }
}
