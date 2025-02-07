<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type(description: 'Test Type with extends')]
final readonly class TestExtendsInterfaceType implements TestInterfaceType
{
    public function __construct(
        public int $id,
        public ?string $name,
        #[Field]
        public DateTimeImmutable $date,
    ) {}

    #[Field(name: 'ID')]
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function cursor(): ?string
    {
        return 'cursor-x';
    }

    #[Field]
    public function getStatus(): string
    {
        return '';
    }
}
