<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\EnumValue;

#[Enum(description: 'Foobar status')]
enum FoobarStatusType: string
{
    case Open = 'open';
    #[EnumValue(description: 'Foobar status Closed', deprecationReason: 'Its deprecated')]
    case Closed = 'closed';
}
