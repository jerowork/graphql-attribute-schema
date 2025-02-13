<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType;

use Jerowork\GraphqlAttributeSchema\Attribute\Cursor;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InterfaceType;

#[InterfaceType(description: 'A description')]
abstract readonly class AbstractTestInterfaceType
{
    public function __construct(
        #[Field]
        public string $constructId,
    ) {}

    #[Cursor]
    #[Field]
    abstract public function getStatus(): ?string;

    #[Field]
    public function getValue(): float
    {
        return 0.5;
    }
}
