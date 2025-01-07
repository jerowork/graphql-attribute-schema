<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

final readonly class EnumValueNode
{
    public function __construct(
        public string $value,
        public ?string $description,
        public ?string $deprecationReason,
    ) {}
}
