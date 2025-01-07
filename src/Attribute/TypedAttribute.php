<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\Type;

interface TypedAttribute
{
    /**
     * @return class-string|Type|ScalarType|null
     */
    public function getType(): string|Type|ScalarType|null;
}
