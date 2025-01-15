<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\RetrieveNameForTypeTrait;
use ReflectionClass;
use Override;

final readonly class TypeClassNodeParser implements ClassNodeParser
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
    public function parse(ReflectionClass $class): Node
    {
        $attribute = $this->getAttribute($class, Type::class);

        return new TypeNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->getDescription(),
            $this->classFieldNodesParser->parse($class),
            $this->cursorNodeParser->parse($class),
        );
    }
}
