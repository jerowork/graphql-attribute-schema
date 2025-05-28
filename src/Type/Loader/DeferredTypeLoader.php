<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Type\Loader;

use Stringable;

interface DeferredTypeLoader
{
    /**
     * @param list<string|int|Stringable> $references
     *
     * @return list<DeferredType>
     */
    public function load(array $references): array;
}
