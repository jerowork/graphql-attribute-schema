<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;

final readonly class InputTypeNode implements Node
{
    /**
     * @param class-string $className
     * @param list<FieldNode> $fieldNodes
     */
    public function __construct(
        public string $className,
        public string $name,
        public ?string $description,
        public array $fieldNodes,
    ) {}

    public function getClassName(): string
    {
        return $this->className;
    }
}
