<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Cursor;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetReferenceTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

final readonly class CursorNodeParser
{
    use GetReferenceTrait;

    private const array RESERVED_METHOD_NAMES = ['__construct'];

    /**
     * @param ReflectionClass<object> $class
     *
     * @throws ParseException
     */
    public function parse(ReflectionClass $class): ?CursorNode
    {
        $cursorNode = null;

        /**
         * @var ReflectionProperty $property
         * @var Cursor $cursorAttribute
         */
        foreach ($this->parseProperties($class) as [$property, $cursorAttribute]) {
            if ($cursorNode !== null) {
                throw ParseException::multipleCursorsFound($class->getName());
            }

            $reference = $this->getReference($property->getType(), $cursorAttribute);

            if ($reference === null) {
                throw ParseException::invalidConnectionPropertyType($class->getName(), $property->getName());
            }

            if ($reference instanceof ScalarReference && $reference->value !== 'string') {
                throw ParseException::invalidConnectionPropertyType($class->getName(), $property->getName());
            }

            $cursorNode = new CursorNode(
                $reference,
                FieldNodeType::Property,
                null,
                $property->getName(),
            );
        }

        /**
         * @var ReflectionMethod $method
         * @var Cursor $cursorAttribute
         */
        foreach ($this->parseMethods($class) as [$method, $cursorAttribute]) {
            if ($cursorNode !== null) {
                throw ParseException::multipleCursorsFound($class->getName());
            }

            $returnType = $method->getReturnType();

            $reference = $this->getReference($returnType, $cursorAttribute);

            if ($reference === null) {
                throw ParseException::invalidConnectionReturnType($class->getName(), $method->getName());
            }

            if ($reference instanceof ScalarReference && $reference->value !== 'string') {
                throw ParseException::invalidConnectionReturnType($class->getName(), $method->getName());
            }

            $cursorNode = new CursorNode(
                $reference,
                FieldNodeType::Method,
                $method->getName(),
                null,
            );
        }

        return $cursorNode;
    }

    /**
     * @param ReflectionClass<object> $class
     *
     * @return list<array{ReflectionProperty, Cursor}>
     */
    private function parseProperties(ReflectionClass $class): array
    {
        $properties = [];

        foreach ($class->getProperties() as $property) {
            $cursorAttributes = $property->getAttributes(Cursor::class);

            if ($cursorAttributes === []) {
                continue;
            }

            $properties[] = [$property, array_pop($cursorAttributes)->newInstance()];
        }

        return $properties;
    }

    /**
     * @param ReflectionClass<object> $class
     *
     * @return list<array{ReflectionMethod, Cursor}>
     */
    private function parseMethods(ReflectionClass $class): array
    {
        $methods = [];

        foreach ($class->getMethods() as $method) {
            if (in_array($method->getName(), self::RESERVED_METHOD_NAMES, true)) {
                continue;
            }

            $cursorAttributes = $method->getAttributes(Cursor::class);

            if ($cursorAttributes === []) {
                continue;
            }

            $methods[] = [$method, array_pop($cursorAttributes)->newInstance()];
        }

        return $methods;
    }
}
