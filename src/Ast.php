<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use Jerowork\GraphqlAttributeSchema\Node\AliasedNode;
use Jerowork\GraphqlAttributeSchema\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Node\InterfaceTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;

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

    /**
     * Index of nodes by class name, with aliases as a fallback, for O(1) lookups in getNodeByClassName().
     *
     * @var array<class-string, Node>
     */
    private array $nodesByName;

    public function __construct(Node ...$nodes)
    {
        $this->nodes = array_values($nodes);

        // Index class names first so they take precedence over aliases.
        $nodesByName = [];

        foreach ($this->nodes as $node) {
            $nodesByName[$node->getClassName()] ??= $node;
        }

        foreach ($this->nodes as $node) {
            if ($node instanceof AliasedNode) {
                $alias = $node->getAlias();

                if ($alias !== null) {
                    $nodesByName[$alias] ??= $node;
                }
            }
        }

        $this->nodesByName = $nodesByName;
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
        return $this->nodesByName[$className] ?? null;
    }

    /**
     * @return list<TypeNode|InterfaceTypeNode>
     */
    public function getNodesImplementingInterface(): array
    {
        $nodes = [];

        foreach ($this->nodes as $node) {
            if (!$node instanceof InterfaceTypeNode && !$node instanceof TypeNode) {
                continue;
            }

            if ($node->implementsInterfaces === []) {
                continue;
            }

            $nodes[] = $node;
        }

        return $nodes;
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
