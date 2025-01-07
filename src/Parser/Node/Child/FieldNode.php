<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

/**
 * @phpstan-import-type ArgNodePayload from ArgNode
 * @phpstan-import-type TypePayload from Type
 *
 * @phpstan-type FieldNodePayload array{
 *     type: TypePayload,
 *     name: string,
 *     description: null|string,
 *     argNodes: list<ArgNodePayload>,
 *     fieldType: string,
 *     methodName: null|string,
 *     propertyName: null|string,
 *     deprecationReason: null|string
 * }
 *
 * @implements ArraySerializable<FieldNodePayload>
 */
final readonly class FieldNode implements ArraySerializable
{
    /**
     * @param list<ArgNode> $argNodes
     */
    public function __construct(
        public Type $type,
        public string $name,
        public ?string $description,
        public array $argNodes,
        public FieldNodeType $fieldType,
        public ?string $methodName,
        public ?string $propertyName,
        public ?string $deprecationReason,
    ) {}

    public function toArray(): array
    {
        return [
            'type' => $this->type->toArray(),
            'name' => $this->name,
            'description' => $this->description,
            'argNodes' => array_map(fn($argNode) => $argNode->toArray(), $this->argNodes),
            'fieldType' => $this->fieldType->value,
            'methodName' => $this->methodName,
            'propertyName' => $this->propertyName,
            'deprecationReason' => $this->deprecationReason,
        ];
    }

    public static function fromArray(array $payload): FieldNode
    {
        return new self(
            Type::fromArray($payload['type']),
            $payload['name'],
            $payload['description'],
            array_map(fn($argNodePayload) => ArgNode::fromArray($argNodePayload), $payload['argNodes']),
            FieldNodeType::from($payload['fieldType']),
            $payload['methodName'],
            $payload['propertyName'],
            $payload['deprecationReason'],
        );
    }
}
