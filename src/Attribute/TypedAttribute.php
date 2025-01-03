<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;

interface TypedAttribute
{
    /**
     * @return class-string|ScalarType|null
     */
    public function getType(): string|ScalarType|null;

    public function isRequired(): bool;
}
