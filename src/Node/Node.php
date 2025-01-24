<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

/**
 * @extends ArraySerializable<array<string, mixed>>
 *
 * @internal
 */
interface Node extends ArraySerializable
{
    /**
     * @return class-string
     */
    public function getClassName(): string;
}
