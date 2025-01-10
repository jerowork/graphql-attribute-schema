<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\RetrieveNameForTypeTrait;
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
            $attribute->getDescription(),
            $this->classFieldNodesParser->parse($class),
        );
    }
}
