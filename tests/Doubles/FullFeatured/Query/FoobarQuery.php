<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use DateTimeImmutable;

#[Query(name: 'getFoobar', type: new ListType(ScalarType::String))]
final readonly class FoobarQuery
{
    /**
     * @param list<bool> $values
     *
     * @return list<string>
     */
    public function __invoke(
        int $id,
        DateTimeImmutable $date,
        #[Arg(type: new ListType(ScalarType::Bool))]
        array $values,
    ): array {
        return [];
    }
}
