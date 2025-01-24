<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use ReflectionParameter;

/**
 * @internal
 */
final readonly class EdgeArgsNodeParser
{
    public function parse(ReflectionParameter $parameter): EdgeArgsNode
    {
        return new EdgeArgsNode(
            $parameter->getName(),
        );
    }
}
