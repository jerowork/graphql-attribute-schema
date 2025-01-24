<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\RetrieveNameForTypeTrait;
use ReflectionClass;
use Override;

final readonly class InputTypeClassNodeParser implements ClassNodeParser
{
    use RetrieveNameForTypeTrait;
    use GetAttributeTrait;

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
        $attribute = $this->getAttribute($class, InputType::class);

        return new InputTypeNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->description,
            $this->classFieldNodesParser->parse($class),
        );
    }
}
