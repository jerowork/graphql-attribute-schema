<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]
final readonly class Cursor implements TypedAttribute
{
    /**
     * @param null|class-string|NullableType|ScalarType $type
     */
    public function __construct(
        public null|NullableType|ScalarType|string $type = null,
    ) {}

    public function getType(): null|NullableType|ScalarType|string
    {
        return $this->type;
    }
}
