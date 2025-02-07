<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\UserType;

final readonly class WithListOutputQuery
{
    /**
     * @return list<UserType>
     */
    #[Query(type: new NullableType(new ListType(UserType::class)))]
    public function withListOutput(): array
    {
        return [];
    }
}
