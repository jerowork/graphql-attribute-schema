<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type(description: 'A foobar')]
final readonly class FoobarType
{
    public function __construct(
        #[Field(name: 'foobarId', description: 'A foobar ID')]
        public string $id,
        #[Field]
        public ?FoobarStatusType $status,
    ) {}

    // @phpstan-ignore-next-line
    #[Field(description: 'A foobar date')]
    public function getDate(
        #[Arg(name: 'limiting')]
        string $limit,
        #[Arg(description: 'The value')]
        ?int $value,
    ): ?DateTimeImmutable {
        return new DateTimeImmutable();
    }
}
