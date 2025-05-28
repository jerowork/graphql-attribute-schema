<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Type\Loader;

use Stringable;

final readonly class DeferredType
{
    public function __construct(
        public int|string|Stringable $reference,
        public object $type,
    ) {}
}
