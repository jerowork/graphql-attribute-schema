<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;

/**
 * @phpstan-import-type FieldNodePayload from FieldNode
 * @phpstan-import-type CursorNodePayload from CursorNode
 *
 * @phpstan-type TypeNodePayload array{
 *     className: class-string,
 *     name: string,
 *     description: null|string,
 *     fieldNodes: list<FieldNodePayload>,
 *     cursorNode: null|CursorNodePayload
 * }
 *
 * @internal
 */
final readonly class TypeNode implements Node
{
    /**
     * @param class-string $className
     * @param list<FieldNode> $fieldNodes
     */
    public function __construct(
        public string $className,
        public string $name,
        public ?string $description,
        public array $fieldNodes,
        public ?CursorNode $cursorNode,
    ) {}

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return TypeNodePayload
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
            'name' => $this->name,
            'description' => $this->description,
            'fieldNodes' => array_map(fn($fieldNode) => $fieldNode->toArray(), $this->fieldNodes),
            'cursorNode' => $this->cursorNode?->toArray(),
        ];
    }

    /**
     * @param TypeNodePayload $payload
     */
    public static function fromArray(array $payload): TypeNode
    {
        return new self(
            $payload['className'],
            $payload['name'],
            $payload['description'],
            array_map(fn($fieldNodePayload) => FieldNode::fromArray($fieldNodePayload), $payload['fieldNodes']),
            $payload['cursorNode'] !== null ? CursorNode::fromArray($payload['cursorNode']) : null,
        );
    }
}
