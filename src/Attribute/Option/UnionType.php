<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute\Option;

final readonly class UnionType implements Type
{
    /**
     * @var list<class-string>
     */
    public array $objectTypes;

    /**
     * @param class-string ...$objectTypes
     */
    public function __construct(
        public string $name,
        string ...$objectTypes,
    ) {
        $this->objectTypes = array_values($objectTypes);
    }
}
