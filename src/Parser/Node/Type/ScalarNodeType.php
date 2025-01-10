<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

/**
 * @phpstan-type ScalarNodeTypePayload array{
 *     value: string,
 *     isValueNullable: bool,
 *     isList: bool,
 *     isListNullable: bool
 * }
 */
final class ScalarNodeType implements ListableNodeType
{
    use NodeTypeTrait;
    use ListableNodeTypeTrait;

    public function __construct(
        public readonly string $value,
        bool $isValueNullable,
        bool $isList,
        bool $isListNullable,
    ) {
        $this->isValueNullable = $isValueNullable;
        $this->isList = $isList;
        $this->isListNullable = $isListNullable;
    }

    public static function create(string $value): self
    {
        return new self($value, false, false, false);
    }

    /**
     * @return ScalarNodeTypePayload
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'isValueNullable' => $this->isValueNullable(),
            'isList' => $this->isList(),
            'isListNullable' => $this->isListNullable(),
        ];
    }

    /**
     * @param ScalarNodeTypePayload $payload
     */
    public static function fromArray(array $payload): ScalarNodeType
    {
        return new self(
            $payload['value'],
            $payload['isValueNullable'],
            $payload['isList'],
            $payload['isListNullable'],
        );
    }

    public function equals(NodeType $type): bool
    {
        return $type instanceof self
            && $type->value === $this->value
            && $type->isValueNullable === $this->isValueNullable
            && $type->isList === $this->isList
            && $type->isListNullable === $this->isListNullable;
    }
}
