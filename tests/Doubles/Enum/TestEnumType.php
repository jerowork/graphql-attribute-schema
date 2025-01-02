<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;

#[Enum(description: 'Test Enum')]
enum TestEnumType: string
{
    case A = 'a';
    case B = 'b';
    case C = 'c';
    case D = 'd';
}
