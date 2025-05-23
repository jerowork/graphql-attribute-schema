<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute\Option;

final readonly class ConnectionType implements Type
{
    /**
     * @param class-string $edgeType
     */
    public function __construct(
        public string $edgeType,
    ) {}
}
