<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use DateTimeImmutable;

#[Query(name: 'testQuery', description: 'Test query')]
final readonly class TestQuery
{
    public function __construct() {}

    public function __invoke(DateTimeImmutable $date, string $id): string
    {
        return '';
    }
}
