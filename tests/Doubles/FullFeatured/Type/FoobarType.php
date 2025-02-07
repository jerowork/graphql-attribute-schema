<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;

#[Type(description: 'A foobar')]
final readonly class FoobarType
{
    public function __construct(
        #[Field(name: 'foobarId', description: 'A foobar ID')]
        public string $id,
        #[Field]
        public ?FoobarStatusType $status,
    ) {}

    #[Field(description: 'A foobar date')]
    public function getDate(
        #[Autowire]
        DateTimeImmutable $service,
        #[Arg(name: 'limiting')]
        string $limit,
        #[Arg(description: 'The value')]
        ?int $value,
    ): ?DateTimeImmutable {
        return new DateTimeImmutable();
    }

    #[Field(type: new NullableType(new ConnectionType(AgentType::class)))]
    public function getUsers(EdgeArgs $edgeArgs, ?string $status): Connection
    {
        return new Connection([]);
    }

    /**
     * @return list<AgentType>
     */
    #[Field(type: new NullableType(new ListType(AgentType::class)))]
    public function getUsersList(): array
    {
        return [];
    }
}
