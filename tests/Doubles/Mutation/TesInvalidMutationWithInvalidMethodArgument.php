<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use DateTimeImmutable;

#[Mutation(description: 'Test mutation')]
final readonly class TesInvalidMutationWithInvalidMethodArgument
{
    // @phpstan-ignore-next-line
    public function __invoke(
        DateTimeImmutable $date,
        $id,
    ): string {
        return '';
    }
}
