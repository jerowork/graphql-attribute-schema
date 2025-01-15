<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Reference;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;

/**
 * @extends ArraySerializable<array>
 */
interface Reference extends ArraySerializable
{
    public function setNullableValue(): self;

    public function isValueNullable(): bool;

    public function equals(Reference $reference): bool;
}
