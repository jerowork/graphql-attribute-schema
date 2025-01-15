<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;

/**
 * @phpstan-type CursorNodePayload array{
 *     reference: array{
 *          type: class-string<Reference>,
 *          payload: array<string, mixed>
 *     },
 *     fieldType: string,
 *     methodName: null|string,
 *     propertyName: null|string
 * }
 *
 * @implements ArraySerializable<CursorNodePayload>
 */
final readonly class CursorNode implements ArraySerializable
{
    public function __construct(
        public Reference $reference,
        public FieldNodeType $fieldType,
        public ?string $methodName,
        public ?string $propertyName,
    ) {}

    public function toArray(): array
    {
        // @phpstan-ignore-next-line
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

    public static function fromArray(array $payload): CursorNode
    {
        /** @var class-string<Reference> $referenceClass */
        $referenceClass = $payload['reference']['type'];

        /** @var Reference $reference */
        $reference = $referenceClass::fromArray($payload['reference']['payload']);

        return new self(
            $reference,
            FieldNodeType::from($payload['fieldType']),
            $payload['methodName'],
            $payload['propertyName'],
        );
    }
}
