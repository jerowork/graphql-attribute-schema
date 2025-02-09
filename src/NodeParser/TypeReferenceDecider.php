<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ObjectType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\Type;
use Jerowork\GraphqlAttributeSchema\Attribute\TypedAttribute;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ListableTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use LogicException;
use ReflectionNamedType;
use ReflectionType;

/**
 * @internal
 */
final readonly class TypeReferenceDecider
{
    private const array ALLOWED_SCALAR_TYPES = ['float', 'string', 'int', 'bool'];

    /**
     * @throws ParseException
     */
    public function getTypeReference(?ReflectionType $reflectionType, ?TypedAttribute $attribute): ?TypeReference
    {
        // Retrieve from attribute if set
        if ($attribute?->getType() !== null) {
            $attributeType = $attribute->getType();

            if ($attributeType instanceof ConnectionType) {
                return ConnectionTypeReference::create($attributeType->edgeType, $attributeType->first);
            }

            if ($attributeType instanceof ListType) {
                if ($attributeType->type instanceof NullableType) {
                    $type = $this->getReferenceFromAttribute($attributeType->type->type);

                    if (!$type instanceof ListableTypeReference) {
                        throw ParseException::invalidListTypeConfiguration($type::class);
                    }

                    return $type->setList()->setNullableValue();
                }

                $type = $this->getReferenceFromAttribute($attributeType->type);

                if (!$type instanceof ListableTypeReference) {
                    throw ParseException::invalidListTypeConfiguration($type::class);
                }

                return $type->setList();
            }

            if ($attributeType instanceof NullableType) {
                if ($attributeType->type instanceof ConnectionType) {
                    return ConnectionTypeReference::create($attributeType->type->edgeType, $attributeType->type->first)
                        ->setNullableValue();
                }

                if ($attributeType->type instanceof ListType) {
                    if ($attributeType->type->type instanceof NullableType) {
                        $type = $this->getReferenceFromAttribute($attributeType->type->type->type);

                        if (!$type instanceof ListableTypeReference) {
                            throw ParseException::invalidListTypeConfiguration($type::class);
                        }

                        return $type->setList()->setNullableList()->setNullableValue();
                    }

                    $type = $this->getReferenceFromAttribute($attributeType->type->type);

                    if (!$type instanceof ListableTypeReference) {
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
            $type = ScalarTypeReference::create($reflectionType->getName());
        } else {
            /** @var class-string $className */
            $className = $reflectionType->getName();

            $type = ObjectTypeReference::create($className);
        }

        return $reflectionType->allowsNull() ? $type->setNullableValue() : $type;
    }

    private function getReferenceFromAttribute(ScalarType|string|Type $type): TypeReference
    {
        if ($type instanceof ScalarType) {
            return ScalarTypeReference::create($type->value);
        }

        if ($type instanceof ObjectType) {
            return ObjectTypeReference::create($type->className);
        }

        if (is_string($type)) {
            /** @var class-string $type */
            return ObjectTypeReference::create($type);
        }

        throw new LogicException('Failed to determine reference from Attribute');
    }
}
