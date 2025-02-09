<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\Type;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]
final readonly class Field implements NamedAttribute, TypedAttribute
{
    /**
     * @param null|class-string|Type|ScalarType $type
     */
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public null|ScalarType|string|Type $type = null,
        public ?string $deprecationReason = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): null|ScalarType|string|Type
    {
        return $this->type;
    }
}
