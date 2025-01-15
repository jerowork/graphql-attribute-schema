<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Type\Connection;

final readonly class EdgeArgs
{
    public function __construct(
        public ?int $first,
        public ?string $after,
        public ?int $last,
        public ?string $before,
    ) {}
}
