<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;

final readonly class InputTypeNode implements Node
{
    /**
     * @param list<FieldNode> $fieldNodes
     */
    public function __construct(
        public Type $type,
        public string $name,
        public ?string $description,
        public array $fieldNodes,
    ) {}

    public function getType(): Type
    {
        return $this->type;
    }
}
