<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Type implements GraphQLAttribute
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
    ) {}
}
