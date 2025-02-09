<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

/**
 * @internal
 */
interface AliasedNode
{
    /**
     * @return null|class-string
     */
    public function getAlias(): ?string;
}
