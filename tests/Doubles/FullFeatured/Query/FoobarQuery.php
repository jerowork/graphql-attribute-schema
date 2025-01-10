<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use DateTimeImmutable;

final readonly class FoobarQuery
{
    /**
     * @param list<bool> $values
     *
     * @return list<string>
     */
    #[Query(name: 'getFoobar', description: 'Get a Foobar', type: new ListType(ScalarType::String))]
    public function __invoke(
        ?int $id,
        DateTimeImmutable $date,
        #[Arg(description: 'List of values', type: new ListType(ScalarType::Bool))]
        array $values,
    ): array {
        return [];
    }
}
