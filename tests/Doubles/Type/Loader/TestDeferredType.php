<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader;

final readonly class TestDeferredType
{
    public function __construct(
        public string $id,
    ) {}
}
