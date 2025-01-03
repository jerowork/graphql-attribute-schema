<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

interface TypedAttribute
{
    public function getType(): ?string;

    public function isRequired(): bool;
}
