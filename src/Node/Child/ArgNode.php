<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\Child;

use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;

/**
 * @phpstan-type ArgNodePayload array{
 *     reference: array{
 *         type: class-string<TypeReference>,
 *         payload: array<string, mixed>
 *     },
 *     name: string,
 *     description: null|string,
 *     propertyName: string
 * }
 *
 * @internal
 */
final readonly class ArgNode implements ArgumentNode
{
    public function __construct(
        public TypeReference $reference,
        public string $name,
        public ?string $description,
        public string $propertyName,
    ) {}

    /**
     * @return ArgNodePayload
     */
    public function toArray(): array
    {
        return [
            'reference' => [
                'type' => $this->reference::class,
                'payload' => $this->reference->toArray(),
            ],
            'name' => $this->name,
            'description' => $this->description,
            'propertyName' => $this->propertyName,
        ];
    }

    /**
     * @param ArgNodePayload $payload
     */
    public static function fromArray(array $payload): ArgNode
    {
        /** @var class-string<TypeReference> $reference */
        $reference = $payload['reference']['type'];

        return new self(
            $reference::fromArray($payload['reference']['payload']),
            $payload['name'],
            $payload['description'],
            $payload['propertyName'],
        );
    }
}
