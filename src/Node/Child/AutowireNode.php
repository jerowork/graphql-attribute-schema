<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\Child;

/**
 * @phpstan-type AutowireNodePayload array{
 *     service: string|class-string,
 *     propertyName: string,
 * }
 *
 * @internal
 */
final readonly class AutowireNode implements ArgumentNode
{
    public function __construct(
        public string $service,
        public string $propertyName,
    ) {}

    /**
     * @return AutowireNodePayload
     */
    public function toArray(): array
    {
        return [
            'service' => $this->service,
            'propertyName' => $this->propertyName,
        ];
    }

    /**
     * @param AutowireNodePayload $payload
     */
    public static function fromArray(array $payload): AutowireNode
    {
        return new self(
            $payload['service'],
            $payload['propertyName'],
        );
    }
}
