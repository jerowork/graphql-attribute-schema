<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\Type;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Mutation implements BaseAttribute, TypedAttribute
{
    /**
     * @param class-string|Type|ScalarType|null $type
     */
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public string|Type|ScalarType|null $type = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getType(): string|Type|ScalarType|null
    {
        return $this->type;
    }
}
