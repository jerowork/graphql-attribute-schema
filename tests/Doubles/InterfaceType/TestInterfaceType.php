<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType;

use Jerowork\GraphqlAttributeSchema\Attribute\Cursor;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InterfaceType;

#[InterfaceType]
interface TestInterfaceType
{
    #[Field(name: 'ID')]
    public function getId(): int;

    #[Field]
    public function getName(): ?string;

    #[Cursor]
    public function cursor(): ?string;
}
