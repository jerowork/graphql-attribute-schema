<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

enum FieldNodeType: string
{
    case Property = 'property';
    case Method = 'method';
}
