<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;

final readonly class Ast
{
    /**
     * @var list<Node>
     */
    private array $nodes;

    public function __construct(Node ...$nodes)
    {
        $this->nodes = array_values($nodes);
    }

    /**
     * @template T of Node
     *
     * @param class-string<T> $type
     *
     * @return list<T>
     */
    public function getNodesByType(string $type): array
    {
        return array_values(array_filter($this->nodes, fn($node) => $node instanceof $type));
    }

    /**
     * @param class-string $typeId
     */
    public function getNodeByTypeId(string $typeId): ?Node
    {
        foreach ($this->nodes as $node) {
            if ($node->typeId !== $typeId) {
                continue;
            }

            return $node;
        }

        return null;
    }
}
