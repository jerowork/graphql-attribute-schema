<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;

/**
 * @phpstan-import-type FieldNodePayload from FieldNode
 *
 * @phpstan-type InputTypeNodePayload array{
 *     className: class-string,
 *     name: string,
 *     description: null|string,
 *     fieldNodes: list<FieldNodePayload>
 * }
 *
 * @internal
 */
final readonly class InputTypeNode implements Node
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
    ) {}

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return InputTypeNodePayload
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
            'name' => $this->name,
            'description' => $this->description,
            'fieldNodes' => array_map(fn($fieldNode) => $fieldNode->toArray(), $this->fieldNodes),
        ];
    }

    /**
     * @param InputTypeNodePayload $payload
     */
    public static function fromArray(array $payload): InputTypeNode
    {
        return new self(
            $payload['className'],
            $payload['name'],
            $payload['description'],
            array_map(fn($fieldNodePayload) => FieldNode::fromArray($fieldNodePayload), $payload['fieldNodes']),
        );
    }
}
