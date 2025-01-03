<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\EnumValue;

#[Enum(description: 'Test Enum')]
enum TestEnumType: string
{
    case A = 'a';
    case B = 'b';
    #[EnumValue(description: 'Case C')]
    case C = 'c';
    case D = 'd';
}
