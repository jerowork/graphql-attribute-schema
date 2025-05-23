<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ObjectType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\Type;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\UnionType;
use Jerowork\GraphqlAttributeSchema\Attribute\TypedAttribute;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ListableTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\UnionTypeReference;
use LogicException;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;

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
                return ConnectionTypeReference::create($attributeType->edgeType);
            }

            if ($attributeType instanceof ListType) {
                if ($attributeType->type instanceof NullableType) {
                    $type = $this->getReferenceFromAttribute($attributeType->type->type, $reflectionType);

                    if (!$type instanceof ListableTypeReference) {
                        throw ParseException::invalidListTypeConfiguration($type::class);
                    }

                    return $type->setList()->setNullableValue();
                }

                $type = $this->getReferenceFromAttribute($attributeType->type, $reflectionType);

                if (!$type instanceof ListableTypeReference) {
                    throw ParseException::invalidListTypeConfiguration($type::class);
                }

                return $type->setList();
            }

            if ($attributeType instanceof NullableType) {
                if ($attributeType->type instanceof ConnectionType) {
                    return ConnectionTypeReference::create($attributeType->type->edgeType)
                        ->setNullableValue();
                }

                if ($attributeType->type instanceof ListType) {
                    if ($attributeType->type->type instanceof NullableType) {
                        $type = $this->getReferenceFromAttribute($attributeType->type->type->type, $reflectionType);

                        if (!$type instanceof ListableTypeReference) {
                            throw ParseException::invalidListTypeConfiguration($type::class);
                        }

                        return $type->setList()->setNullableList()->setNullableValue();
                    }

                    $type = $this->getReferenceFromAttribute($attributeType->type->type, $reflectionType);

                    if (!$type instanceof ListableTypeReference) {
                        throw ParseException::invalidListTypeConfiguration($type::class);
                    }

                    return $type->setList()->setNullableList();
                }

                return $this->getReferenceFromAttribute($attributeType->type, $reflectionType)
                    ->setNullableValue();
            }

            return $this->getReferenceFromAttribute($attributeType, $reflectionType);
        }

        // Retrieve from class

        if ($reflectionType instanceof ReflectionUnionType) {
            /** @var list<class-string> $classNames */
            $classNames = array_values(array_map(
                fn($type) => $type->getName(),
                array_filter(
                    $reflectionType->getTypes(),
                    fn($type) => $type instanceof ReflectionNamedType && !$type->isBuiltin(),
                ),
            ));

            $type = UnionTypeReference::create(
                sprintf('Union_%s', implode('_', array_map(
                    fn($classname) => array_values(array_reverse(explode('\\', $classname)))[0],
                    $classNames,
                ))),
                $classNames,
            );

            return $reflectionType->allowsNull() ? $type->setNullableValue() : $type;
        }

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

    private function getReferenceFromAttribute(ScalarType|string|Type $type, ?ReflectionType $reflectionType): TypeReference
    {
        if ($type instanceof ScalarType) {
            return ScalarTypeReference::create($type->value);
        }

        if ($type instanceof ObjectType) {
            return ObjectTypeReference::create($type->className);
        }

        if ($type instanceof UnionType) {
            $classNames = $type->objectTypes;

            if ($classNames === []) {
                if (!$reflectionType instanceof ReflectionUnionType) {
                    throw new LogicException('UnionType is missing union types as return type');
                }

                /** @var list<class-string> $classNames */
                $classNames = array_values(array_map(
                    fn($returnType) => $returnType->getName(),
                    array_filter(
                        $reflectionType->getTypes(),
                        fn($type) => $type instanceof ReflectionNamedType && !$type->isBuiltin(),
                    ),
                ));
            }

            return UnionTypeReference::create($type->name, $classNames);
        }

        if (is_string($type)) {
            /** @var class-string $type */
            return ObjectTypeReference::create($type);
        }

        throw new LogicException('Failed to determine reference from Attribute');
    }
}
