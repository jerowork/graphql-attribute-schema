<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\NodeType;

/**
 * @phpstan-type ArgNodePayload array{
 *     type: array{
 *         type: class-string<NodeType>,
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
        public NodeType $type,
        public string $name,
        public ?string $description,
        public string $propertyName,
    ) {}

    public function toArray(): array
    {
        // @phpstan-ignore-next-line
        return [
            'type' => [
                'type' => $this->type::class,
                'payload' => $this->type->toArray(),
            ],
            'name' => $this->name,
            'description' => $this->description,
            'propertyName' => $this->propertyName,
        ];
    }

    public static function fromArray(array $payload): ArgNode
    {
        /** @var class-string<NodeType> $type */
        $type = $payload['type']['type'];

        return new self(
            $type::fromArray($payload['type']['payload']), // @phpstan-ignore-line
            $payload['name'],
            $payload['description'],
            $payload['propertyName'],
        );
    }
}
