<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\Child;

/**
 * @phpstan-type EdgeArgsNodePayload array{
 *     propertyName: string,
 * }
 *
 * @internal
 */
final readonly class EdgeArgsNode implements ArgumentNode
{
    public function __construct(
        public string $propertyName,
    ) {}

    /**
     * @return EdgeArgsNodePayload
     */
    public function toArray(): array
    {
        return [
            'propertyName' => $this->propertyName,
        ];
    }

    /**
     * @param EdgeArgsNodePayload $payload
     */
    public static function fromArray(array $payload): EdgeArgsNode
    {
        return new self(
            $payload['propertyName'],
        );
    }
}
