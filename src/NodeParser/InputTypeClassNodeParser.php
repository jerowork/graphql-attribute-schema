<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldsNodeParser;
use ReflectionClass;
use Override;
use ReflectionMethod;

final readonly class InputTypeClassNodeParser implements NodeParser
{
    use RetrieveNameForTypeTrait;
    use GetAttributeTrait;

    public function __construct(
        private ClassFieldsNodeParser $classFieldsNodeParser,
    ) {}

    #[Override]
    public function supports(string $attribute): bool
    {
        return $attribute === InputType::class;
    }

    #[Override]
    public function parse(ReflectionClass $class, ?ReflectionMethod $method): Node
    {
        $attribute = $this->getAttribute($class, InputType::class);

        return new InputTypeNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->description,
            $this->classFieldsNodeParser->parse($class),
        );
    }
}
