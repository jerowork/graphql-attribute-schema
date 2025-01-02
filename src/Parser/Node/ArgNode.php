<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

final readonly class ArgNode implements Node
{
    /**
     * @param class-string|null $typeId
     */
    public function __construct(
        public ?string $typeId,
        public ?string $type,
        public string $name,
        public ?string $description,
        public bool $isRequired,
        public string $propertyName,
    ) {}
}
