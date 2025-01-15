<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;

/**
 * @phpstan-type EdgeArgsNodePayload array{
 *     propertyName: string,
 * }
 *
 * @implements ArraySerializable<EdgeArgsNodePayload>
 */
final readonly class EdgeArgsNode implements ArraySerializable
{
    public function __construct(
        public string $propertyName,
    ) {}

    public function toArray(): array
    {
        return [
            'propertyName' => $this->propertyName,
        ];
    }

    public static function fromArray(array $payload): EdgeArgsNode
    {
        return new self(
            $payload['propertyName'],
        );
    }
}
