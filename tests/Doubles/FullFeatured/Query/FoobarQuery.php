<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\UserType;

final readonly class FoobarQuery
{
    /**
     * @param list<bool> $values
     *
     * @return list<UserType>
     */
    #[Query(name: 'getFoobar', description: 'Get a Foobar', type: new ListType(UserType::class))]
    public function __invoke(
        ?int $id,
        DateTimeImmutable $date,
        #[Arg(description: 'List of values', type: new ListType(ScalarType::Bool))]
        array $values,
    ): array {
        return [];
    }
}
