<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Query;

final readonly class DeprecatedQuery
{
    // @phpstan-ignore-next-line
    #[Query(deprecationReason: 'This is deprecated.')]
    public function doSomeWork(): ?string
    {
        return null;
    }
}
