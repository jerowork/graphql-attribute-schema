<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\NodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Util\Finder\Finder;
use Jerowork\GraphqlAttributeSchema\Util\Reflector\Reflector;
use Generator;
use ReflectionClass;

final readonly class Parser
{
    private const array SUPPORTED_ATTRIBUTES = [
        Mutation::class,
        Query::class,
        Type::class,
        InputType::class,
        Enum::class,
    ];

    /**
     * @param iterable<NodeParser> $nodeParsers
     */
    public function __construct(
        private Finder $finder,
        private Reflector $reflector,
        private iterable $nodeParsers,
    ) {}

    /**
     * @throws ParseException
     */
    public function parse(string ...$dirs): Ast
    {
        $nodes = [];

        foreach ($this->getClasses(...$dirs) as $class) {
            $attribute = $this->getSupportedAttribute($class);

            if ($attribute === null) {
                continue;
            }

            foreach ($this->nodeParsers as $nodeParser) {
                if (!$nodeParser->supports($attribute)) {
                    continue;
                }

                $nodes[] = $nodeParser->parse($class);
            }
        }

        return new Ast(...$nodes);
    }

    /**
     * @return Generator<ReflectionClass<object>>
     */
    private function getClasses(string ...$dirs): Generator
    {
        foreach ($this->finder->findFiles(...$dirs) as $file) {
            yield from $this->reflector->getClasses($file);
        }
    }

    /**
     * @param ReflectionClass<object> $class
     *
     * @return class-string|null
     */
    private function getSupportedAttribute(ReflectionClass $class): ?string
    {
        foreach (self::SUPPORTED_ATTRIBUTES as $attribute) {
            if ($class->getAttributes($attribute) === []) {
                continue;
            }

            return $attribute;
        }

        return null;
    }
}
