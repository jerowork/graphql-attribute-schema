<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;

#[Enum(description: 'Foobar status')]
enum FoobarStatusType: string
{
    case Open = 'open';
    case Closed = 'closed';
}
