<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

trait NodeTypeTrait
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
