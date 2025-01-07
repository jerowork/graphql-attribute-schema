<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute\Option;

final readonly class ListType implements Type
{
    /**
     * @param class-string|Type|ScalarType $type
     */
    public function __construct(
        public string|Type|ScalarType $type,
    ) {}
}
