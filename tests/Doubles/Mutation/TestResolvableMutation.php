<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestResolvableInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestSmallInputType;

#[Mutation]
final readonly class TestResolvableMutation
{
    /**
     * @param list<string> $userIds
     * @param list<TestSmallInputType> $smallInputs
     */
    public function __invoke(
        string $id,
        TestResolvableInputType $input,
        #[Arg(type: new ListType(ScalarType::String))]
        array $userIds,
        #[Arg(type: new ListType(TestSmallInputType::class))]
        array $smallInputs,
        DateTimeImmutable $dateTime,
    ): string {
        return sprintf(
            'Mutation has been called with id %s and input with name %s, userIds: %s, parentNames: %s, smallInputs: %s',
            $id,
            $input->name,
            implode(', ', $userIds),
            implode(', ', $input->parentNames),
            implode(', ', array_map(fn($item) => $item->id, $smallInputs)),
        );
    }
}
