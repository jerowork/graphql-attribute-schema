<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ObjectType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\Type as OptionType;
use Jerowork\GraphqlAttributeSchema\Attribute\TypedAttribute;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ListableReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use ReflectionNamedType;
use ReflectionType;
use LogicException;

trait GetReferenceTrait
{
    private const array ALLOWED_SCALAR_TYPES = ['float', 'string', 'int', 'bool'];

    /**
     * @throws ParseException
     */
    public function getReference(?ReflectionType $reflectionType, ?TypedAttribute $attribute): ?Reference
    {
        // Retrieve from attribute if set
        if ($attribute?->getType() !== null) {
            $attributeType = $attribute->getType();

            if ($attributeType instanceof ListType) {
                if ($attributeType->type instanceof NullableType) {
                    $type = $this->getReferenceFromAttribute($attributeType->type->type);

                    if (!$type instanceof ListableReference) {
                        throw ParseException::invalidListTypeConfiguration($type::class);
                    }

                    return $type->setList()->setNullableValue();
                }

                $type = $this->getReferenceFromAttribute($attributeType->type);

                if (!$type instanceof ListableReference) {
                    throw ParseException::invalidListTypeConfiguration($type::class);
                }

                return $type->setList();
            }

            if ($attributeType instanceof NullableType) {
                if ($attributeType->type instanceof ListType) {
                    if ($attributeType->type->type instanceof NullableType) {
                        $type = $this->getReferenceFromAttribute($attributeType->type->type->type);

                        if (!$type instanceof ListableReference) {
                            throw ParseException::invalidListTypeConfiguration($type::class);
                        }

                        return $type->setList()->setNullableList()->setNullableValue();
                    }

                    $type = $this->getReferenceFromAttribute($attributeType->type->type);

                    if (!$type instanceof ListableReference) {
                        throw ParseException::invalidListTypeConfiguration($type::class);
                    }

                    return $type->setList()->setNullableList();
                }

                return $this->getReferenceFromAttribute($attributeType->type)
                    ->setNullableValue();
            }

            return $this->getReferenceFromAttribute($attributeType);
        }

        // Retrieve from class
        if (!$reflectionType instanceof ReflectionNamedType) {
            return null;
        }

        if ($reflectionType->isBuiltin() && !in_array($reflectionType->getName(), self::ALLOWED_SCALAR_TYPES, true)) {
            return null;
        }

        if ($reflectionType->isBuiltin()) {
            $type = ScalarReference::create($reflectionType->getName());
        } else {
            /** @var class-string $className */
            $className = $reflectionType->getName();

            $type = ObjectReference::create($className);
        }

        return $reflectionType->allowsNull() ? $type->setNullableValue() : $type;
    }

    private function getReferenceFromAttribute(string|OptionType|ScalarType $type): Reference
    {
        if ($type instanceof ScalarType) {
            return ScalarReference::create($type->value);
        }

        if ($type instanceof ObjectType) {
            return ObjectReference::create($type->className);
        }

        if (is_string($type)) {
            /** @var class-string $type */
            return ObjectReference::create($type);
        }

        throw new LogicException('Failed to determine reference from Attribute');
    }
}
