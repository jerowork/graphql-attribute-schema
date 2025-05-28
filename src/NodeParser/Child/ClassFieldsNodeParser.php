<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\NodeParser\RetrieveNameForFieldTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionProperty;
use Stringable;

/**
 * @internal
 */
final readonly class ClassFieldsNodeParser
{
    use RetrieveNameForFieldTrait;

    private const array ALLOWED_SCALAR_TYPES_FOR_DEFERRED_TYPE_LOADER = ['string', 'int', 'array'];

    private const array RESERVED_METHOD_NAMES = ['__construct'];

    public function __construct(
        private TypeReferenceDecider $typeReferenceDecider,
        private MethodArgumentsNodeParser $methodArgumentsNodeParser,
    ) {}

    /**
     * @throws ParseException
     *
     * @return list<FieldNode>
     */
    public function parse(ReflectionClass $class): array
    {
        $fieldNodes = [];

        /**
         * @var ReflectionProperty $property
         * @var Field $fieldAttribute
         */
        foreach ($this->parseProperties($class) as [$property, $fieldAttribute]) {
            $reference = $this->typeReferenceDecider->getTypeReference($property->getType(), $fieldAttribute);

            if ($reference === null) {
                throw ParseException::invalidPropertyType($class->getName(), $property->getName());
            }

            // When reference is ConnectionType, the property needs to have Connection as type
            if ($reference instanceof ConnectionTypeReference) {
                if (!$property->getType() instanceof ReflectionNamedType || $property->getType()->getName() !== Connection::class) {
                    throw ParseException::invalidConnectionPropertyType($class->getName(), $property->getName());
                }
            }

            // When it has a deferred type loader, the return type needs to be an integer, string or Stringable
            if ($fieldAttribute->deferredTypeLoader !== null) {
                if ($property->getType() === null) {
                    throw ParseException::missingDeferredTypeLoaderReturnType($class->getName(), $property->getName());
                }

                if ($property->getType() instanceof ReflectionNamedType
                    && $property->getType()->isBuiltin()
                    && !in_array($property->getType()->getName(), self::ALLOWED_SCALAR_TYPES_FOR_DEFERRED_TYPE_LOADER, true)
                ) {
                    throw ParseException::invalidDeferredTypeLoaderReturnType($class->getName(), $property->getName());
                }

                if (!$property->getType() instanceof Stringable) {
                    throw ParseException::invalidDeferredTypeLoaderReturnType($class->getName(), $property->getName());
                }
            }

            $fieldNodes[] = new FieldNode(
                $reference,
                $fieldAttribute->name ?? $property->getName(),
                $fieldAttribute->description,
                [],
                FieldNodeType::Property,
                null,
                $property->getName(),
                $fieldAttribute->deprecationReason,
                $fieldAttribute->deferredTypeLoader,
            );
        }

        /**
         * @var ReflectionMethod $method
         * @var Field $fieldAttribute
         */
        foreach ($this->parseMethods($class) as [$method, $fieldAttribute]) {
            $returnType = $method->getReturnType();

            $reference = $this->typeReferenceDecider->getTypeReference($returnType, $fieldAttribute);

            if ($reference === null) {
                throw ParseException::invalidReturnType($class->getName(), $method->getName());
            }

            // When reference is ConnectionType, the property needs to have Connection as return type
            if ($reference instanceof ConnectionTypeReference) {
                if (!$returnType instanceof ReflectionNamedType || $returnType->getName() !== Connection::class) {
                    throw ParseException::invalidConnectionReturnType($class->getName(), $method->getName());
                }
            }

            // When it has a deferred type loader, the return type needs to be an integer, string or Stringable
            if ($fieldAttribute->deferredTypeLoader !== null) {
                if ($returnType === null) {
                    throw ParseException::missingDeferredTypeLoaderReturnType($class->getName(), $method->getName());
                }

                if ($returnType instanceof ReflectionNamedType
                    && $returnType->isBuiltin()
                    && !in_array($returnType->getName(), self::ALLOWED_SCALAR_TYPES_FOR_DEFERRED_TYPE_LOADER, true)
                ) {
                    throw ParseException::invalidDeferredTypeLoaderReturnType($class->getName(), $method->getName());
                }

                if (!$returnType instanceof Stringable) {
                    throw ParseException::invalidDeferredTypeLoaderReturnType($class->getName(), $method->getName());
                }
            }

            $fieldNodes[] = new FieldNode(
                $reference,
                $this->retrieveNameForField($method, $fieldAttribute),
                $fieldAttribute->description,
                array_values([...$this->methodArgumentsNodeParser->parse($method)]),
                FieldNodeType::Method,
                $method->getName(),
                null,
                $fieldAttribute->deprecationReason,
                $fieldAttribute->deferredTypeLoader,
            );
        }

        return $fieldNodes;
    }

    /**
     * @return list<array{ReflectionProperty, Field}>
     */
    private function parseProperties(ReflectionClass $class): array
    {
        $properties = [];

        foreach ($class->getProperties() as $property) {
            $fieldAttributes = $property->getAttributes(Field::class);

            if ($fieldAttributes === []) {
                continue;
            }

            $properties[] = [$property, array_pop($fieldAttributes)->newInstance()];
        }

        return $properties;
    }

    /**
     * @return list<array{ReflectionMethod, Field}>
     */
    private function parseMethods(ReflectionClass $class): array
    {
        $methods = [];

        foreach ($class->getMethods() as $method) {
            if (in_array($method->getName(), self::RESERVED_METHOD_NAMES, true)) {
                continue;
            }

            $fieldAttributes = $method->getAttributes(Field::class);

            if ($fieldAttributes === []) {
                continue;
            }

            $methods[] = [$method, array_pop($fieldAttributes)->newInstance()];
        }

        return $methods;
    }
}
