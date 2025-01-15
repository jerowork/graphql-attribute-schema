<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;

final readonly class TestInvalidQueryWithInvalidConnectionReturnType
{
    #[Query(type: new ConnectionType(TestType::class))]
    public function __invoke(): array
    {
        return [];
    }
}
