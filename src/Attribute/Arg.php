<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class Arg implements GraphQLAttribute
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
    ) {}
}
