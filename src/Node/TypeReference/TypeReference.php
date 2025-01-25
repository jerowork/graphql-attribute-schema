<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\TypeReference;

use Jerowork\GraphqlAttributeSchema\Node\ArraySerializable;

/**
 * @internal
 */
interface TypeReference extends ArraySerializable
{
    public function setNullableValue(): self;

    public function isValueNullable(): bool;

    public function equals(TypeReference $reference): bool;
}
