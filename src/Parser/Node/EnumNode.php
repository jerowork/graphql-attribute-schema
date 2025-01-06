<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

final readonly class EnumNode implements Node
{
    /**
     * @param class-string $className
     * @param list<EnumValueNode> $cases
     */
    public function __construct(
        public string $className,
        public string $name,
        public ?string $description,
        public array $cases,
    ) {}

    public function getClassName(): string
    {
        return $this->className;
    }
}
