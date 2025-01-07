<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

/**
 * @phpstan-import-type TypePayload from Type
 *
 * @phpstan-type ArgNodePayload array{
 *     type: TypePayload,
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
        public Type $type,
        public string $name,
        public ?string $description,
        public string $propertyName,
    ) {}

    public function toArray(): array
    {
        return [
            'type' => $this->type->toArray(),
            'name' => $this->name,
            'description' => $this->description,
            'propertyName' => $this->propertyName,
        ];
    }

    public static function fromArray(array $payload): ArgNode
    {
        return new self(
            Type::fromArray($payload['type']),
            $payload['name'],
            $payload['description'],
            $payload['propertyName'],
        );
    }
}
