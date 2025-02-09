<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\Type;

interface TypedAttribute
{
    /**
     * @return null|class-string|Type|ScalarType
     */
    public function getType(): null|ScalarType|string|Type;
}
