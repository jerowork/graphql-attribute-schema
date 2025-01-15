<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Reference;

/**
 * @phpstan-type ScalarNodeTypePayload array{
 *     value: string,
 *     isValueNullable: bool,
 *     isList: bool,
 *     isListNullable: bool
 * }
 */
final class ScalarReference implements ListableReference
{
    use ReferenceTrait;
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
    public static function fromArray(array $payload): ScalarReference
    {
        return new self(
            $payload['value'],
            $payload['isValueNullable'],
            $payload['isList'],
            $payload['isListNullable'],
        );
    }

    public function equals(Reference $reference): bool
    {
        return $reference instanceof self
            && $reference->value === $this->value
            && $reference->isValueNullable === $this->isValueNullable
            && $reference->isList === $this->isList
            && $reference->isListNullable === $this->isListNullable;
    }
}
