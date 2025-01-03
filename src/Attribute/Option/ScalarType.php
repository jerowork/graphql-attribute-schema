<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute\Option;

enum ScalarType: string
{
    case String = 'string';
    case Int = 'int';
    case Float = 'float';
    case Bool = 'bool';
}
