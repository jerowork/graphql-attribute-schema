<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

final readonly class EnumNode implements Node
{
    /**
     * @param class-string $typeId
     * @param list<string> $cases
     */
    public function __construct(
        public string $typeId,
        public string $name,
        public ?string $description,
        public array $cases,
    ) {}

    public function getTypeId(): string
    {
        return $this->typeId;
    }
}
