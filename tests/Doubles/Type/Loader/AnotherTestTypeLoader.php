<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader;

use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredTypeLoader;

final readonly class AnotherTestTypeLoader implements DeferredTypeLoader
{
    public function load(array $references): array
    {
        return [];
    }
}
