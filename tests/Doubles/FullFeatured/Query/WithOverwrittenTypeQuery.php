<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;

final readonly class WithOverwrittenTypeQuery
{
    #[Query(type: new NullableType(ScalarType::Bool))]
    public function withOverwrittenType(): string
    {
        return 'string';
    }
}
