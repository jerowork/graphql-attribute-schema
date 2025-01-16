<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\Class;

use Jerowork\GraphqlAttributeSchema\Node\ArraySerializable;

/**
 * @phpstan-type EnumValueNodePayload array{
 *     value: string,
 *     description: null|string,
 *     deprecationReason: null|string
 * }
 *
 * @implements ArraySerializable<EnumValueNodePayload>
 */
final readonly class EnumValueNode implements ArraySerializable
{
    public function __construct(
        public string $value,
        public ?string $description,
        public ?string $deprecationReason,
    ) {}

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'description' => $this->description,
            'deprecationReason' => $this->deprecationReason,
        ];
    }

    public static function fromArray(array $payload): EnumValueNode
    {
        return new self(
            $payload['value'],
            $payload['description'],
            $payload['deprecationReason'],
        );
    }
}
