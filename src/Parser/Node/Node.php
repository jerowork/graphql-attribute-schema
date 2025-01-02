<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

interface Node
{
    public ?string $typeId {
        get;
    }

    public string $name {
        get;
    }
}
