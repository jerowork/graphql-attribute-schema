<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

/**
 * @phpstan-import-type ArgNodePayload from ArgNode
 * @phpstan-import-type AutowireNodePayload from AutowireNode
 * @phpstan-import-type TypePayload from Type
 *
 * @phpstan-type FieldNodePayload array{
 *     type: TypePayload,
 *     name: string,
 *     description: null|string,
 *     argumentNodes: list<array{
 *          node: class-string<ArgNode|AutowireNode>,
 *          payload: ArgNodePayload|AutowireNodePayload
 *     }>,
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
     * @param list<ArgNode|AutowireNode> $argumentNodes
     */
    public function __construct(
        public Type $type,
        public string $name,
        public ?string $description,
        public array $argumentNodes,
        public FieldNodeType $fieldType,
        public ?string $methodName,
        public ?string $propertyName,
        public ?string $deprecationReason,
    ) {}

    public function toArray(): array
    {
        $argumentNodes = [];
        foreach ($this->argumentNodes as $argumentNode) {
            $argumentNodes[] = [
                'node' => $argumentNode::class,
                'payload' => $argumentNode->toArray(),
            ];
        }

        return [
            'type' => $this->type->toArray(),
            'name' => $this->name,
            'description' => $this->description,
            'argumentNodes' => $argumentNodes,
            'fieldType' => $this->fieldType->value,
            'methodName' => $this->methodName,
            'propertyName' => $this->propertyName,
            'deprecationReason' => $this->deprecationReason,
        ];
    }

    public static function fromArray(array $payload): FieldNode
    {
        $argumentNodes = [];
        foreach ($payload['argumentNodes'] as $argumentNode) {
            $argumentPayload = $argumentNode['payload'];
            if ($argumentNode['node'] === ArgNode::class) {
                /** @var ArgNodePayload $argumentPayload */
                $argumentNodes[] = ArgNode::fromArray($argumentPayload);
            } else {
                /** @var AutowireNodePayload $argumentPayload */
                $argumentNodes[] = AutowireNode::fromArray($argumentPayload);
            }
        }

        return new self(
            Type::fromArray($payload['type']),
            $payload['name'],
            $payload['description'],
            $argumentNodes,
            FieldNodeType::from($payload['fieldType']),
            $payload['methodName'],
            $payload['propertyName'],
            $payload['deprecationReason'],
        );
    }
}
