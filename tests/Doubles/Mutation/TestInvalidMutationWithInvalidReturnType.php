<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use DateTimeImmutable;

final readonly class TestInvalidMutationWithInvalidReturnType
{
    #[Mutation]
    public function mutation(DateTimeImmutable $date, string $id): void {}
}
