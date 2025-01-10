<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;

final readonly class TestMutationWithMultipleMethods
{
    #[Mutation]
    public function methodA(): string
    {
        return '';
    }

    #[Mutation]
    public function methodB(): int
    {
        return 1;
    }
}
