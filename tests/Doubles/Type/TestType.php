<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Cursor;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type(description: 'Test Type')]
final readonly class TestType
{
    public function __construct(
        #[Field(name: 'typeId')]
        public ?string $id,
        #[Field]
        public DateTimeImmutable $date,
    ) {}

    #[Cursor]
    #[Field]
    public function flow(): ?string
    {
        return '';
    }

    #[Field]
    public function getStatus(): string
    {
        return '';
    }
}
