<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;

final readonly class TestInvalidMutationWithInvalidReturnType
{
    #[Mutation]
    public function mutation(DateTimeImmutable $date, string $id): void {}
}
