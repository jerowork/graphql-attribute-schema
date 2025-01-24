<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetTypeReferenceTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\NodeParser\RetrieveNameForFieldTrait;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;
use ReflectionNamedType;

final readonly class ClassFieldNodesParser
{
    use RetrieveNameForFieldTrait;
    use GetTypeReferenceTrait;

    private const array RESERVED_METHOD_NAMES = ['__construct'];

    public function __construct(
        private MethodArgumentNodesParser $methodArgNodesParser,
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
            $reference = $this->getTypeReference($property->getType(), $fieldAttribute);

            if ($reference === null) {
                throw ParseException::invalidPropertyType($class->getName(), $property->getName());
            }

            // When reference is ConnectionType, the property needs to have Connection as type
            if ($reference instanceof ConnectionTypeReference) {
                if (!$property->getType() instanceof ReflectionNamedType || $property->getType()->getName() !== Connection::class) {
                    throw ParseException::invalidConnectionPropertyType($class->getName(), $property->getName());
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
            );
        }

        /**
         * @var ReflectionMethod $method
         * @var Field $fieldAttribute
         */
        foreach ($this->parseMethods($class) as [$method, $fieldAttribute]) {
            $returnType = $method->getReturnType();

            $reference = $this->getTypeReference($returnType, $fieldAttribute);

            if ($reference === null) {
                throw ParseException::invalidReturnType($class->getName(), $method->getName());
            }

            // When reference is ConnectionType, the property needs to have Connection as return type
            if ($reference instanceof ConnectionTypeReference) {
                if (!$returnType instanceof ReflectionNamedType || $returnType->getName() !== Connection::class) {
                    throw ParseException::invalidConnectionReturnType($class->getName(), $method->getName());
                }
            }

            $fieldNodes[] = new FieldNode(
                $reference,
                $this->retrieveNameForField($method, $fieldAttribute),
                $fieldAttribute->description,
                $this->methodArgNodesParser->parse($method),
                FieldNodeType::Method,
                $method->getName(),
                null,
                $fieldAttribute->deprecationReason,
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
