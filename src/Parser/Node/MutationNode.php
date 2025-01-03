<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

final readonly class MutationNode implements Node
{
    /**
     * @param class-string $typeId
     * @param list<ArgNode> $argNodes
     * @param class-string|null $outputTypeId
     */
    public function __construct(
        public string $typeId,
        public string $name,
        public ?string $description,
        public array $argNodes,
        public ?string $outputTypeId,
        public ?string $outputType,
        public bool $isRequired,
        public string $methodName,
    ) {}

    public function getTypeId(): string
    {
        return $this->typeId;
    }
}
