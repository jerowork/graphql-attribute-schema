<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;

/**
 * @phpstan-type ArgNodePayload array{
 *     reference: array{
 *         type: class-string<Reference>,
 *         payload: array<string, mixed>
 *     },
 *     name: string,
 *     description: null|string,
 *     propertyName: string
 * }
 *
 * @implements ArraySerializable<ArgNodePayload>
 */
final readonly class ArgNode implements ArraySerializable
{
    public function __construct(
        public Reference $reference,
        public string $name,
        public ?string $description,
        public string $propertyName,
    ) {}

    public function toArray(): array
    {
        // @phpstan-ignore-next-line
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

    public static function fromArray(array $payload): ArgNode
    {
        /** @var class-string<Reference> $reference */
        $reference = $payload['reference']['type'];

        return new self(
            $reference::fromArray($payload['reference']['payload']), // @phpstan-ignore-line
            $payload['name'],
            $payload['description'],
            $payload['propertyName'],
        );
    }
}
