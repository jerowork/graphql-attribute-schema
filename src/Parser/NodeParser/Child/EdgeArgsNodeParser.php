<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\EdgeArgsNode;
use ReflectionParameter;

final readonly class EdgeArgsNodeParser
{
    public function parse(ReflectionParameter $parameter): EdgeArgsNode
    {
        return new EdgeArgsNode(
            $parameter->getName(),
        );
    }
}
