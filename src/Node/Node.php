<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

/**
 * @internal
 */
interface Node extends ArraySerializable
{
    /**
     * @return class-string
     */
    public function getClassName(): string;
}
