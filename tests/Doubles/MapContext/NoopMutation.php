<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\MapContext;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;

final readonly class NoopMutation
{
    #[Mutation]
    public function noop(): string
    {
        return 'ok';
    }
}
