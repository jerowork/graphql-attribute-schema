<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\Child;

/**
 * @phpstan-type ContextNodePayload array{
 *     propertyName: string,
 * }
 *
 * @internal
 */
final readonly class ContextNode implements ArgumentNode
{
    public function __construct(
        public string $propertyName,
    ) {}

    /**
     * @return ContextNodePayload
     */
    public function toArray(): array
    {
        return [
            'propertyName' => $this->propertyName,
        ];
    }

    /**
     * @param ContextNodePayload $payload
     */
    public static function fromArray(array $payload): ContextNode
    {
        return new self(
            $payload['propertyName'],
        );
    }
}
