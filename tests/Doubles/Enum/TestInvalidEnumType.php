<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;

#[Enum]
enum TestInvalidEnumType
{
    case A;
    case B;
    case C;
    case D;
}
