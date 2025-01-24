<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\TypeReference;

/**
 * @phpstan-type ScalarTypeReferencePayload array{
 *     value: string,
 *     isValueNullable: bool,
 *     isList: bool,
 *     isListNullable: bool
 * }
 *
 * @internal
 */
final class ScalarTypeReference implements ListableTypeReference
{
    use TypeReferenceTrait;
    use ListableReferenceTrait;

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
     * @return ScalarTypeReferencePayload
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
     * @param ScalarTypeReferencePayload $payload
     */
    public static function fromArray(array $payload): ScalarTypeReference
    {
        return new self(
            $payload['value'],
            $payload['isValueNullable'],
            $payload['isList'],
            $payload['isListNullable'],
        );
    }

    public function equals(TypeReference $reference): bool
    {
        return $reference instanceof self
            && $reference->value === $this->value
            && $reference->isValueNullable === $this->isValueNullable
            && $reference->isList === $this->isList
            && $reference->isListNullable === $this->isListNullable;
    }
}
