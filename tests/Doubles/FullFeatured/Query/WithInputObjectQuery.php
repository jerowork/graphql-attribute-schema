<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarStatusType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input\QueryInputType;

final readonly class WithInputObjectQuery
{
    #[Query]
    public function query(QueryInputType $input): FoobarStatusType
    {
        return FoobarStatusType::Closed;
    }
}
