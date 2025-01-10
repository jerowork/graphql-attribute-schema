<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;

/**
 * @extends ArraySerializable<array>
 */
interface NodeType extends ArraySerializable
{
    public function setNullableValue(): self;

    public function isValueNullable(): bool;

    public function equals(NodeType $type): bool;
}
