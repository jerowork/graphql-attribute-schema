<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use DateTimeImmutable;
use DateTimeInterface;
use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;

final readonly class TestMutation
{
    public function __construct() {}

    #[Mutation(description: 'Test mutation')]
    public function testMutation(
        DateTimeImmutable $date,
        #[Arg(name: 'mutationId', description: 'Mutation ID')]
        ?string $id,
    ): string {
        return sprintf(
            'Mutation has been called with date %s and id %s',
            $date->format(DateTimeInterface::RFC3339_EXTENDED),
            $id,
        );
    }
}
