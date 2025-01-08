<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

interface AliasedNode
{
    /**
     * @return class-string|null
     */
    public function getAlias(): ?string;
}
