<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\Child;

enum FieldNodeType: string
{
    case Property = 'property';
    case Method = 'method';
}
