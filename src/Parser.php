<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use Generator;
use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\InterfaceType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\NodeParser\NodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Util\Finder\Finder;
use Jerowork\GraphqlAttributeSchema\Util\Reflector\Reflector;
use ReflectionClass;
use ReflectionMethod;

final readonly class Parser
{
    private const array SUPPORTED_CLASS_ATTRIBUTES = [
        Type::class,
        InterfaceType::class,
        InputType::class,
        Enum::class,
        Scalar::class,
    ];

    private const array SUPPORTED_METHOD_ATTRIBUTES = [
        Mutation::class,
        Query::class,
    ];

    /**
     * @param iterable<class-string> $customTypes
     */
    public function __construct(
        private Finder $finder,
        private Reflector $reflector,
        private NodeParser $nodeParser,
        private iterable $customTypes,
    ) {}

    /**
     * @throws ParseException
     */
    public function parse(string ...$dirs): Ast
    {
        $nodes = [];

        foreach ($this->getClasses(...$dirs) as $class) {
            $nodes = [...$nodes, ...$this->parseClass($class)];
        }

        foreach ($this->customTypes as $customType) {
            $nodes = [...$nodes, ...$this->parseClass(new ReflectionClass($customType))];
        }

        return new Ast(...$nodes);
    }

    /**
     * @throws ParseException
     *
     * @return Generator<Node>
     */
    private function parseClass(ReflectionClass $class): Generator
    {
        // Class attributes
        $attribute = $this->getSupportedAttribute($class);

        if ($attribute !== null) {
            yield from $this->nodeParser->parse($attribute, $class, null);

            return;
        }

        // Method attributes
        foreach ($class->getMethods() as $method) {
            $attribute = $this->getSupportedAttribute($method);

            if ($attribute === null) {
                continue;
            }

            yield from $this->nodeParser->parse($attribute, $class, $method);
        }
    }

    /**
     * @return Generator<ReflectionClass>
     */
    private function getClasses(string ...$dirs): Generator
    {
        foreach ($this->finder->findFiles(...$dirs) as $file) {
            yield from $this->reflector->getClasses($file);
        }
    }

    /**
     * @return null|class-string
     */
    private function getSupportedAttribute(ReflectionClass|ReflectionMethod $reflector): ?string
    {
        $supportedAttributes = $reflector instanceof ReflectionClass ? self::SUPPORTED_CLASS_ATTRIBUTES : self::SUPPORTED_METHOD_ATTRIBUTES;

        foreach ($supportedAttributes as $attribute) {
            if ($reflector->getAttributes($attribute) === []) {
                continue;
            }

            return $attribute;
        }

        return null;
    }
}
