<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

/**
 * @phpstan-type TypePayload array{
 *     value: string,
 *     type: string,
 *     isValueNullable: bool,
 *     isList: bool,
 *     isListNullable: bool
 * }
 *
 * @implements ArraySerializable<TypePayload>
 */
final class Type implements ArraySerializable
{
    private const string SCALAR = 'scalar';
    private const string OBJECT = 'object';

    /**
     * @param class-string|string $value
     */
    public function __construct(
        public readonly string $value,
        private readonly string $type,
        private bool $isValueNullable,
        private bool $isList,
        private bool $isListNullable,
    ) {}

    public static function createScalar(string $value): self
    {
        return new self($value, self::SCALAR, false, false, false);
    }

    /**
     * @param class-string $value
     */
    public static function createObject(string $value): self
    {
        return new self($value, self::OBJECT, false, false, false);
    }

    public function setNullableValue(): self
    {
        $this->isValueNullable = true;

        return $this;
    }

    public function setList(): self
    {
        $this->isList = true;

        return $this;
    }

    public function setNullableList(): self
    {
        $this->isListNullable = true;

        return $this;
    }

    public function isScalar(): bool
    {
        return $this->type === self::SCALAR;
    }

    public function isObject(): bool
    {
        return $this->type === self::OBJECT;
    }

    public function isValueNullable(): bool
    {
        return $this->isValueNullable;
    }

    public function isList(): bool
    {
        return $this->isList;
    }

    public function isListNullable(): bool
    {
        return $this->isListNullable;
    }

    public function equals(Type $type): bool
    {
        return $this->value === $type->value
            && $this->type === $type->type
            && $this->isValueNullable === $type->isValueNullable
            && $this->isList === $type->isList
            && $this->isListNullable === $type->isListNullable;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'type' => $this->type,
            'isValueNullable' => $this->isValueNullable,
            'isList' => $this->isList,
            'isListNullable' => $this->isListNullable,
        ];
    }

    public static function fromArray(array $payload): Type
    {
        return new self(
            $payload['value'],
            $payload['type'],
            $payload['isValueNullable'],
            $payload['isList'],
            $payload['isListNullable'],
        );
    }
}
