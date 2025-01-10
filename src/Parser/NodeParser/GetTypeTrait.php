<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ObjectType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\Type as OptionType;
use Jerowork\GraphqlAttributeSchema\Attribute\TypedAttribute;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ListableNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\NodeType;
use ReflectionNamedType;
use ReflectionType;
use LogicException;

trait GetTypeTrait
{
    private const array ALLOWED_SCALAR_TYPES = ['float', 'string', 'int', 'bool'];

    /**
     * @throws ParseException
     */
    public function getType(?ReflectionType $reflectionType, ?TypedAttribute $attribute): ?NodeType
    {
        // Retrieve from attribute if set
        if ($attribute?->getType() !== null) {
            $attributeType = $attribute->getType();

            if ($attributeType instanceof ListType) {
                if ($attributeType->type instanceof NullableType) {
                    $type = $this->getTypeFromAttribute($attributeType->type->type);

                    if (!$type instanceof ListableNodeType) {
                        throw ParseException::invalidListTypeConfiguration($type::class);
                    }

                    return $type->setList()->setNullableValue();
                }

                $type = $this->getTypeFromAttribute($attributeType->type);

                if (!$type instanceof ListableNodeType) {
                    throw ParseException::invalidListTypeConfiguration($type::class);
                }

                return $type->setList();
            }

            if ($attributeType instanceof NullableType) {
                if ($attributeType->type instanceof ListType) {
                    if ($attributeType->type->type instanceof NullableType) {
                        $type = $this->getTypeFromAttribute($attributeType->type->type->type);

                        if (!$type instanceof ListableNodeType) {
                            throw ParseException::invalidListTypeConfiguration($type::class);
                        }

                        return $type->setList()->setNullableList()->setNullableValue();
                    }

                    $type = $this->getTypeFromAttribute($attributeType->type->type);

                    if (!$type instanceof ListableNodeType) {
                        throw ParseException::invalidListTypeConfiguration($type::class);
                    }

                    return $type->setList()->setNullableList();
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
            $type = ScalarNodeType::create($reflectionType->getName());
        } else {
            /** @var class-string $className */
            $className = $reflectionType->getName();

            $type = ObjectNodeType::create($className);
        }

        return $reflectionType->allowsNull() ? $type->setNullableValue() : $type;
    }

    private function getTypeFromAttribute(string|OptionType|ScalarType $type): NodeType
    {
        if ($type instanceof ScalarType) {
            return ScalarNodeType::create($type->value);
        }

        if ($type instanceof ObjectType) {
            return ObjectNodeType::create($type->className);
        }

        if (is_string($type)) {
            /** @var class-string $type */
            return ObjectNodeType::create($type);
        }

        throw new LogicException('Failed to determine type from Attribute');
    }
}
