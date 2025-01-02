<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;

#[Mutation]
final readonly class TestInvalidMutationWithTooManyMethods
{
    public function methodA(): void {}

    public function methodB(): void {}
}
