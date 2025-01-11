<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;

/**
 * @phpstan-import-type ArgNodePayload from ArgNode
 * @phpstan-import-type AutowireNodePayload from AutowireNode
 *
 * @phpstan-type FieldNodePayload array{
 *     reference: array{
 *          type: class-string<Reference>,
 *          payload: array<string, mixed>
 *     },
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
        public Reference $reference,
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

        // @phpstan-ignore-next-line
        return [
            'reference' => [
                'type' => $this->reference::class,
                'payload' => $this->reference->toArray(),
            ],
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

        /** @var class-string<Reference> $reference */
        $reference = $payload['reference']['type'];

        return new self(
            $reference::fromArray($payload['reference']['payload']), // @phpstan-ignore-line
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
