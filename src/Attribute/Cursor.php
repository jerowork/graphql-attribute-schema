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
     * @param class-string|NullableType|ScalarType|null $type
     */
    public function __construct(
        public string|NullableType|ScalarType|null $type = null,
    ) {}

    public function getType(): string|NullableType|ScalarType|null
    {
        return $this->type;
    }
}
