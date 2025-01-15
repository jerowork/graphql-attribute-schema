<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;

#[Type]
final readonly class TestConnectionMutation
{
    #[Mutation(type: new ConnectionType(TestType::class))]
    public function mutate(EdgeArgs $edgeArgs): Connection
    {
        return new Connection([], true, true);
    }
}
