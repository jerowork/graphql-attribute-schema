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
     * @param class-string<T> $nodeType
     *
     * @return list<T>
     */
    public function getNodesByNodeType(string $nodeType): array
    {
        return array_values(array_filter($this->nodes, fn($node) => $node instanceof $nodeType));
    }

    public function getNodeByClassName(string $className): ?Node
    {
        foreach ($this->nodes as $node) {
            if ($node->getClassName() !== $className) {
                continue;
            }

            return $node;
        }

        return null;
    }
}
