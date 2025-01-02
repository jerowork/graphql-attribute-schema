<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Mutation;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarStatusType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input\MutateFoobarInputType;

#[Mutation(description: 'Mutate a foobar')]
final readonly class FoobarMutation
{
    public function __invoke(MutateFoobarInputType $input): FoobarType
    {
        return new FoobarType(
            '91766214-b2fa-4e4c-ad31-4c474ecdf248',
            FoobarStatusType::Open,
        );
    }
}
