<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;

final readonly class QueryNode implements Node
{
    /**
     * @param list<ArgNode> $argNodes
     */
    public function __construct(
        public Type $type,
        public string $name,
        public ?string $description,
        public array $argNodes,
        public Type $outputType,
        public bool $isRequired,
        public string $methodName,
    ) {}

    public function getType(): Type
    {
        return $this->type;
    }
}
