<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\Child;

/**
 * @phpstan-type MapContextNodePayload array{
 *     className: class-string,
 *     propertyName: string,
 *     nullable: bool,
 * }
 *
 * @internal
 */
final readonly class MapContextNode implements ArgumentNode
{
    /**
     * @param class-string $className
     */
    public function __construct(
        public string $className,
        public string $propertyName,
        public bool $nullable,
    ) {}

    /**
     * @return MapContextNodePayload
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
            'propertyName' => $this->propertyName,
            'nullable' => $this->nullable,
        ];
    }

    /**
     * @param MapContextNodePayload $payload
     */
    public static function fromArray(array $payload): MapContextNode
    {
        return new self(
            $payload['className'],
            $payload['propertyName'],
            $payload['nullable'],
        );
    }
}
