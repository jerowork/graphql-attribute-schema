<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

final readonly class TypeNode implements ClassNode
{
    /**
     * @param class-string $typeId
     * @param list<FieldNode> $fieldNodes
     */
    public function __construct(
        public string $typeId,
        public string $name,
        public ?string $description,
        public array $fieldNodes,
    ) {}
}
