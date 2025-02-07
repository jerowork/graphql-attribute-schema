<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;

final readonly class TestInvalidMutationWithInvalidMethodArgument
{
    #[Mutation(description: 'Test mutation')]
    public function __invoke(
        DateTimeImmutable $date,
        $id,
    ): string {
        return '';
    }
}
