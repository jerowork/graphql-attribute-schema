<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;

#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class Arg implements BaseAttribute, TypedAttribute
{
    /**
     * @param class-string|ScalarType|null $type
     */
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public string|ScalarType|null $type = null,
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

    public function getType(): string|ScalarType|null
    {
        return $this->type;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }
}
