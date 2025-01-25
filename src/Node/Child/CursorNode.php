<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\Child;

use Jerowork\GraphqlAttributeSchema\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;

/**
 * @phpstan-type CursorNodePayload array{
 *     reference: array{
 *          type: class-string<TypeReference>,
 *          payload: array<string, mixed>
 *     },
 *     fieldType: string,
 *     methodName: null|string,
 *     propertyName: null|string
 * }
 *
 * @internal
 */
final readonly class CursorNode implements ArraySerializable
{
    public function __construct(
        public TypeReference $reference,
        public FieldNodeType $fieldType,
        public ?string $methodName,
        public ?string $propertyName,
    ) {}

    /**
     * @return CursorNodePayload
     */
    public function toArray(): array
    {
        return [
            'reference' => [
                'type' => $this->reference::class,
                'payload' => $this->reference->toArray(),
            ],
            'fieldType' => $this->fieldType->value,
            'methodName' => $this->methodName,
            'propertyName' => $this->propertyName,
        ];
    }

    /**
     * @param CursorNodePayload $payload
     */
    public static function fromArray(array $payload): CursorNode
    {
        /** @var class-string<TypeReference> $referenceClass */
        $referenceClass = $payload['reference']['type'];

        /** @var TypeReference $reference */
        $reference = $referenceClass::fromArray($payload['reference']['payload']);

        return new self(
            $reference,
            FieldNodeType::from($payload['fieldType']),
            $payload['methodName'],
            $payload['propertyName'],
        );
    }
}
