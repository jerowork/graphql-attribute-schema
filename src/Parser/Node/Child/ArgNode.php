<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

final readonly class ArgNode
{
    public function __construct(
        public Type $type,
        public string $name,
        public ?string $description,
        public string $propertyName,
    ) {}
}
