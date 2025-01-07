<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute\Option;

final readonly class ObjectType implements Type
{
    /**
     * @param class-string $className
     */
    public function __construct(
        public string $className,
    ) {}
}
