<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetTypeTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\RetrieveNameForFieldTrait;
use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;
use ReflectionNamedType;

final readonly class ClassFieldNodesParser
{
    use RetrieveNameForFieldTrait;
    use GetTypeTrait;

    private const array RESERVED_METHOD_NAMES = ['__construct'];
    private const string RETURN_TYPE_VOID = 'void';

    public function __construct(
        private MethodArgNodesParser $methodArgNodesParser,
    ) {}

    /**
     * @param ReflectionClass<object> $class
     *
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
            $propertyType = $property->getType();

            if (!$propertyType instanceof ReflectionNamedType) {
                throw ParseException::invalidPropertyType($class->getName(), $property->getName());
            }

            $fieldNodes[] = new FieldNode(
                $this->getType($propertyType, $fieldAttribute),
                $fieldAttribute->name ?? $property->getName(),
                $fieldAttribute->description,
                !$propertyType->allowsNull(),
                [],
                FieldNodeType::Property,
                null,
                $property->getName(),
            );
        }

        /**
         * @var ReflectionMethod $method
         * @var Field $fieldAttribute
         */
        foreach ($this->parseMethods($class) as [$method, $fieldAttribute]) {
            $returnType = $method->getReturnType();

            if (!$returnType instanceof ReflectionNamedType) {
                throw ParseException::invalidReturnType($class->getName(), $method->getName());
            }

            if ($returnType->isBuiltin() && $returnType->getName() === self::RETURN_TYPE_VOID) {
                throw ParseException::voidReturnType($class->getName(), $method->getName());
            }

            $fieldNodes[] = new FieldNode(
                $this->getType($returnType, $fieldAttribute),
                $this->retrieveNameForField($method, $fieldAttribute),
                $fieldAttribute->description,
                !$returnType->allowsNull(),
                $this->methodArgNodesParser->parse($method),
                FieldNodeType::Method,
                $method->getName(),
                null,
            );
        }

        return $fieldNodes;
    }

    /**
     * @param ReflectionClass<object> $class
     *
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
     * @param ReflectionClass<object> $class
     *
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
