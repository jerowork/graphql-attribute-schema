<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader;

use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredType;
use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredTypeLoader;

final class TestTypeLoader implements DeferredTypeLoader
{
    public int $isTimesCalled = 0;

    public function load(array $references): array
    {
        ++$this->isTimesCalled;

        return array_map(
            fn($reference) => new DeferredType($reference, new TestDeferredType((string) $reference)),
            $references,
        );
    }
}
