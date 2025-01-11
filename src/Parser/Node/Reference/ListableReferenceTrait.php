<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Reference;

trait ListableReferenceTrait
{
    protected bool $isList;
    protected bool $isListNullable;

    public function setList(): self
    {
        $this->isList = true;

        return $this;
    }

    public function setNullableList(): self
    {
        $this->isListNullable = true;

        return $this;
    }

    public function isList(): bool
    {
        return $this->isList;
    }

    public function isListNullable(): bool
    {
        return $this->isListNullable;
    }
}
