<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

/**
 * @extends ArraySerializable<array<string, mixed>>
 */
interface Node extends ArraySerializable
{
    /**
     * @return class-string
     */
    public function getClassName(): string;
}
