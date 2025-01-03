<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

final readonly class EnumNode implements Node
{
    /**
     * @param list<string> $cases
     */
    public function __construct(
        public Type $type,
        public string $name,
        public ?string $description,
        public array $cases,
    ) {}

    public function getType(): Type
    {
        return $this->type;
    }
}
