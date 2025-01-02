<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

final readonly class FieldNode implements Node
{
    /**
     * @param class-string|null $typeId
     * @param list<ArgNode> $argNodes
     */
    public function __construct(
        public ?string $typeId,
        public ?string $type,
        public string $name,
        public ?string $description,
        public bool $isRequired,
        public array $argNodes,
        public FieldNodeType $fieldType,
        public ?string $methodName,
        public ?string $propertyName,
    ) {}
}
