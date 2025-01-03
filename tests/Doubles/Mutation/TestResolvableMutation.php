<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestResolvableInputType;

#[Mutation]
final readonly class TestResolvableMutation
{
    public function __invoke(string $id, TestResolvableInputType $input): string
    {
        return sprintf(
            'Mutation has been called with id %s and input with name %s',
            $id,
            $input->name,
        );
    }
}
