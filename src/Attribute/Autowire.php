<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class Autowire
{
    /**
     * @param string|class-string $service
     */
    public function __construct(
        public ?string $service = null,
    ) {}
}
