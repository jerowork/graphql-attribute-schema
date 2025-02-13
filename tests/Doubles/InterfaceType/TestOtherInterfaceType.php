<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InterfaceType;

#[InterfaceType]
interface TestOtherInterfaceType extends TestSubInterfaceType
{
    #[Field]
    public function getOtherName(): string;
}
