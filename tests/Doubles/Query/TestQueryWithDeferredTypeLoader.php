<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Query;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader\TestTypeLoader;

final readonly class TestQueryWithDeferredTypeLoader
{
    public function __construct() {}

    #[Query(name: 'testQuery', description: 'Test query', deferredTypeLoader: TestTypeLoader::class)]
    public function __invoke(DateTimeImmutable $date, string $id): string
    {
        return '';
    }
}
