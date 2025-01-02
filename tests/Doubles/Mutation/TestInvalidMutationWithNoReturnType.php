<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use DateTimeImmutable;

#[Mutation]
final readonly class TestInvalidMutationWithNoReturnType
{
    public function __invoke(DateTimeImmutable $date, string $id): void {}
}
