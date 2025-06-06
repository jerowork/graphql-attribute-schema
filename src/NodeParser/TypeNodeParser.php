<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Generator;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\CursorNodeParser;
use Override;
use ReflectionClass;
use ReflectionMethod;

/**
 * @internal
 */
final readonly class TypeNodeParser implements NodeParser
{
    use RetrieveNameForTypeTrait;
    use GetAttributeTrait;
    use GetInterfaceTypesTrait;

    public function __construct(
        private ClassFieldsNodeParser $classFieldsNodeParser,
        private CursorNodeParser $cursorNodeParser,
    ) {}

    #[Override]
    public function parse(string $attribute, ReflectionClass $class, ?ReflectionMethod $method): Generator
    {
        if ($attribute !== Type::class) {
            return;
        }

        $attribute = $this->getAttribute($class, Type::class);

        yield new TypeNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->description,
            $this->classFieldsNodeParser->parse($class),
            $this->cursorNodeParser->parse($class),
            $this->getInterfaceTypes($class),
        );
    }
}
