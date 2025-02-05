<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class TestInvalidType
{
    public function __construct(
        #[Field(name: 'typeId')]
        public ?string $id,
        #[Field]
        public DateTimeImmutable $date,
    ) {}

    #[Field]
    public function flow(): void {}

    #[Field]
    public function getStatus(): string
    {
        return '';
    }
}
