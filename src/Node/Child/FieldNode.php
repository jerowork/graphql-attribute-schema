<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\Child;

use Jerowork\GraphqlAttributeSchema\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredTypeLoader;

/**
 * @phpstan-import-type ArgNodePayload from ArgNode
 * @phpstan-import-type AutowireNodePayload from AutowireNode
 * @phpstan-import-type EdgeArgsNodePayload from EdgeArgsNode
 *
 * @phpstan-type FieldNodePayload array{
 *     reference: array{
 *          type: class-string<TypeReference>,
 *          payload: array<string, mixed>
 *     },
 *     name: string,
 *     description: null|string,
 *     argumentNodes: list<array{
 *          node: class-string<ArgumentNode>,
 *          payload: ArgNodePayload|AutowireNodePayload|EdgeArgsNodePayload
 *     }>,
 *     fieldType: string,
 *     methodName: null|string,
 *     propertyName: null|string,
 *     deprecationReason: null|string,
 *     deferredTypeLoader: null|class-string<DeferredTypeLoader>
 * }
 *
 * @internal
 */
final readonly class FieldNode implements ArraySerializable
{
    /**
     * @param list<ArgumentNode> $argumentNodes
     * @param null|class-string<DeferredTypeLoader> $deferredTypeLoader
     */
    public function __construct(
        public TypeReference $reference,
        public string $name,
        public ?string $description,
        public array $argumentNodes,
        public FieldNodeType $fieldType,
        public ?string $methodName,
        public ?string $propertyName,
        public ?string $deprecationReason,
        public ?string $deferredTypeLoader,
    ) {}

    /**
     * @return FieldNodePayload
     */
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
            'deferredTypeLoader' => $this->deferredTypeLoader,
        ];
    }

    /**
     * @param FieldNodePayload $payload
     */
    public static function fromArray(array $payload): FieldNode
    {
        $argumentNodes = [];
        foreach ($payload['argumentNodes'] as $argumentNode) {
            $argumentPayload = $argumentNode['payload'];

            if ($argumentNode['node'] === ArgNode::class) {
                /** @var ArgNodePayload $argumentPayload */
                $argumentNodes[] = ArgNode::fromArray($argumentPayload);
            } elseif ($argumentNode['node'] === EdgeArgsNode::class) {
                /** @var EdgeArgsNodePayload $argumentPayload */
                $argumentNodes[] = EdgeArgsNode::fromArray($argumentPayload);
            } else {
                /** @var AutowireNodePayload $argumentPayload */
                $argumentNodes[] = AutowireNode::fromArray($argumentPayload);
            }
        }

        /** @var class-string<TypeReference> $reference */
        $reference = $payload['reference']['type'];

        return new self(
            $reference::fromArray($payload['reference']['payload']),
            $payload['name'],
            $payload['description'],
            $argumentNodes,
            FieldNodeType::from($payload['fieldType']),
            $payload['methodName'],
            $payload['propertyName'],
            $payload['deprecationReason'],
            $payload['deferredTypeLoader'],
        );
    }
}
