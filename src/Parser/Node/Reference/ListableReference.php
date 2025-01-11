<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Reference;

interface ListableReference extends Reference
{
    public function setList(): self;

    public function setNullableList(): self;

    public function isList(): bool;

    public function isListNullable(): bool;
}
