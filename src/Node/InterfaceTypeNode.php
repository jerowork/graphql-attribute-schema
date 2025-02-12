<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;

/**
 * @phpstan-import-type FieldNodePayload from FieldNode
 * @phpstan-import-type CursorNodePayload from CursorNode
 *
 * @phpstan-type InterfaceTypeNodePayload array{
 *     className: class-string,
 *     name: string,
 *     description: null|string,
 *     fieldNodes: list<FieldNodePayload>,
 *     cursorNode: null|CursorNodePayload,
 *     implementsInterfaces: list<class-string>
 * }
 *
 * @internal
 */
final readonly class InterfaceTypeNode implements Node
{
    /**
     * @param class-string $className
     * @param list<FieldNode> $fieldNodes
     * @param list<class-string> $implementsInterfaces
     */
    public function __construct(
        public string $className,
        public string $name,
        public ?string $description,
        public array $fieldNodes,
        public ?CursorNode $cursorNode,
        public array $implementsInterfaces,
    ) {}

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return InterfaceTypeNodePayload
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
            'name' => $this->name,
            'description' => $this->description,
            'fieldNodes' => array_map(fn($fieldNode) => $fieldNode->toArray(), $this->fieldNodes),
            'cursorNode' => $this->cursorNode?->toArray(),
            'implementsInterfaces' => $this->implementsInterfaces,
        ];
    }

    /**
     * @param InterfaceTypeNodePayload $payload
     */
    public static function fromArray(array $payload): InterfaceTypeNode
    {
        return new self(
            $payload['className'],
            $payload['name'],
            $payload['description'],
            array_map(fn($fieldNodePayload) => FieldNode::fromArray($fieldNodePayload), $payload['fieldNodes']),
            $payload['cursorNode'] !== null ? CursorNode::fromArray($payload['cursorNode']) : null,
            $payload['implementsInterfaces'],
        );
    }
}
