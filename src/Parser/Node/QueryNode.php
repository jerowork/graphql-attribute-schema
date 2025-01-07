<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;

final readonly class QueryNode implements Node
{
    /**
     * @param class-string $className
     * @param list<ArgNode> $argNodes
     */
    public function __construct(
        public string $className,
        public string $name,
        public ?string $description,
        public array $argNodes,
        public Type $outputType,
        public string $methodName,
    ) {}

    public function getClassName(): string
    {
        return $this->className;
    }
}
