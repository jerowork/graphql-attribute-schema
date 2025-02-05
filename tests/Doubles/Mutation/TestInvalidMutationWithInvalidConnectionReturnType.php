<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;

final readonly class TestInvalidMutationWithInvalidConnectionReturnType
{
    /**
     * @return array<mixed>
     */
    #[Mutation(type: new ConnectionType(TestType::class))]
    public function mutation(DateTimeImmutable $date, string $id): array
    {
        return [];
    }
}
