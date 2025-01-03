<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

enum TypeType: string
{
    case Scalar = 'scalar';
    case Object = 'object';
}
