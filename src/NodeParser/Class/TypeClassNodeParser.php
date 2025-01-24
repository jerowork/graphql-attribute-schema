<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\NodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\RetrieveNameForTypeTrait;
use ReflectionClass;
use Override;
use ReflectionMethod;

final readonly class TypeClassNodeParser implements NodeParser
{
    use RetrieveNameForTypeTrait;
    use GetAttributeTrait;

    public function __construct(
        private ClassFieldNodesParser $classFieldNodesParser,
        private CursorNodeParser $cursorNodeParser,
    ) {}

    #[Override]
    public function supports(string $attribute): bool
    {
        return $attribute === Type::class;
    }

    #[Override]
    public function parse(ReflectionClass $class, ?ReflectionMethod $method): Node
    {
        $attribute = $this->getAttribute($class, Type::class);

        return new TypeNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->description,
            $this->classFieldNodesParser->parse($class),
            $this->cursorNodeParser->parse($class),
        );
    }
}
