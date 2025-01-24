<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\TypeReference;

/**
 * @internal
 */
trait TypeReferenceTrait
{
    protected bool $isValueNullable;

    public function setNullableValue(): self
    {
        $this->isValueNullable = true;

        return $this;
    }

    public function isValueNullable(): bool
    {
        return $this->isValueNullable;
    }
}
