<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

interface ClassNode extends Node
{
    public string $typeId {
        get;
    }
}
