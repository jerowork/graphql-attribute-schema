<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Generator;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldsNodeParser;
use Override;
use ReflectionClass;
use ReflectionMethod;

/**
 * @internal
 */
final readonly class InputTypeNodeParser implements NodeParser
{
    use RetrieveNameForTypeTrait;
    use GetAttributeTrait;

    public function __construct(
        private ClassFieldsNodeParser $classFieldsNodeParser,
    ) {}

    #[Override]
    public function parse(string $attribute, ReflectionClass $class, ?ReflectionMethod $method): Generator
    {
        if ($attribute !== InputType::class) {
            return;
        }

        $attribute = $this->getAttribute($class, InputType::class);

        yield new InputTypeNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->description,
            $this->classFieldsNodeParser->parse($class),
        );
    }
}
