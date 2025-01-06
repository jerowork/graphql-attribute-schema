<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

final readonly class FieldNode implements Node
{
    /**
     * @param list<ArgNode> $argNodes
     */
    public function __construct(
        public Type $type,
        public string $name,
        public ?string $description,
        public bool $isRequired,
        public array $argNodes,
        public FieldNodeType $fieldType,
        public ?string $methodName,
        public ?string $propertyName,
    ) {}

    public function getType(): Type
    {
        return $this->type;
    }
}
