<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference;

interface ListableTypeReference extends TypeReference
{
    public function setList(): self;

    public function setNullableList(): self;

    public function isList(): bool;

    public function isListNullable(): bool;
}
