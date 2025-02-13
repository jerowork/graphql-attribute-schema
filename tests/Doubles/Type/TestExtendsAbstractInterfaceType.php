<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\AbstractTestInterfaceType;

#[Type(description: 'Test Type with extends abstract')]
final readonly class TestExtendsAbstractInterfaceType extends AbstractTestInterfaceType
{
    public function __construct(
        public int $id,
        public ?string $name,
        #[Field]
        public DateTimeImmutable $date,
    ) {
        parent::__construct((string) $id);
    }

    #[Field(name: 'ID')]
    public function getId(): int
    {
        return $this->id;
    }

    #[Field]
    public function getStatus(): ?string
    {
        return '';
    }
}
