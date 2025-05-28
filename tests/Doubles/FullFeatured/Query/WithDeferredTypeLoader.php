<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader\TestTypeLoader;

final readonly class WithDeferredTypeLoader
{
    #[Query(name: 'withDeferredTypeLoader', description: 'A description', deferredTypeLoader: TestTypeLoader::class)]
    public function __invoke(): string
    {
        return 'reference';
    }
}
