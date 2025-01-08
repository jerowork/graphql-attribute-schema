<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;

/**
 * @phpstan-type AutowireNodePayload array{
 *     service: string|class-string,
 *     propertyName: string,
 * }
 *
 * @implements ArraySerializable<AutowireNodePayload>
 */
final readonly class AutowireNode implements ArraySerializable
{
    public function __construct(
        public string $service,
        public string $propertyName,
    ) {}

    public function toArray(): array
    {
        return [
            'service' => $this->service,
            'propertyName' => $this->propertyName,
        ];
    }

    public static function fromArray(array $payload): AutowireNode
    {
        return new self(
            $payload['service'],
            $payload['propertyName'],
        );
    }
}
