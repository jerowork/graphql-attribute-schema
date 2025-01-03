<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]
final readonly class Field implements BaseAttribute, TypedAttribute
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public ?string $type = null,
        public bool $isRequired = true,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }
}
