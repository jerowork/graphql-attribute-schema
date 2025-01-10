<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

interface ListableNodeType extends NodeType
{
    public function setList(): self;

    public function setNullableList(): self;

    public function isList(): bool;

    public function isListNullable(): bool;
}
