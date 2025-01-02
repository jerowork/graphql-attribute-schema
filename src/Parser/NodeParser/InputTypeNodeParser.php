<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use ReflectionClass;
use Override;

final readonly class InputTypeNodeParser implements NodeParser
{
    use RetrieveNameForTypeTrait;
    use GetClassAttributeTrait;

    public function __construct(
        private ClassFieldNodesParser $classFieldNodesParser,
    ) {}

    #[Override]
    public function supports(string $attribute): bool
    {
        return $attribute === InputType::class;
    }

    #[Override]
    public function parse(ReflectionClass $class): Node
    {
        $attribute = $this->getClassAttribute($class, InputType::class);

        return new InputTypeNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->description,
            $this->classFieldNodesParser->parse($class),
        );
    }
}
