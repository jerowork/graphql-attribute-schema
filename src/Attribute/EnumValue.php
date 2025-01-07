<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final readonly class EnumValue
{
    public function __construct(
        public ?string $description = null,
        public ?string $deprecationReason = null,
    ) {}
}
