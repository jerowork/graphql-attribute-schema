<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use Jerowork\GraphqlAttributeSchema\Node\AliasedNode;
use Jerowork\GraphqlAttributeSchema\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Node\Node;

/**
 * @phpstan-type AstPayload array{
 *     nodes: list<array{
 *          node: class-string,
 *          payload: array<string, mixed>
 *     }>
 * }
 */
final readonly class Ast implements ArraySerializable
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

    /**
     * @param class-string $className
     */
    public function getNodeByClassName(string $className): ?Node
    {
        foreach ($this->nodes as $node) {
            if ($node->getClassName() !== $className) {
                continue;
            }

            return $node;
        }

        // Try to retrieve node by alias
        foreach ($this->nodes as $node) {
            if (!$node instanceof AliasedNode) {
                continue;
            }

            if ($node->getAlias() !== $className) {
                continue;
            }

            return $node;
        }

        return null;
    }

    /**
     * @return AstPayload
     */
    public function toArray(): array
    {
        $nodes = [];
        foreach ($this->nodes as $node) {
            $nodes[] = [
                'node' => $node::class,
                'payload' => $node->toArray(),
            ];
        }

        return [
            'nodes' => $nodes,
        ];
    }

    /**
     * @param AstPayload $payload
     */
    public static function fromArray(array $payload): Ast
    {
        $nodes = [];
        foreach ($payload['nodes'] as $nodePayload) {
            /** @var Node $nodeClassName */
            $nodeClassName = $nodePayload['node'];
            $nodes[] = $nodeClassName::fromArray($nodePayload['payload']);
        }

        /** @var list<Node> $nodes */
        return new self(...$nodes);
    }
}
