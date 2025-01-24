<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

/**
 * @internal
 */
interface AliasedNode
{
    /**
     * @return class-string|null
     */
    public function getAlias(): ?string;
}
