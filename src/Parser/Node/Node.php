<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

interface Node
{
    /**
     * @return class-string
     */
    public function getClassName(): string;
}
