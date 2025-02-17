<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\TypeReference;

/**
 * @phpstan-type UnionTypeReferencePayload array{
 *     name: string,
 *     classNames: list<class-string>,
 *     isValueNullable: bool,
 *     isList: bool,
 *     isListNullable: bool
 * }
 *
 * @internal
 */
final class UnionTypeReference implements ListableTypeReference
{
    use TypeReferenceTrait;
    use ListableReferenceTrait;

    /**
     * @param list<class-string> $classNames
     */
    public function __construct(
        public readonly string $name,
        public readonly array $classNames,
        bool $isValueNullable,
        bool $isList,
        bool $isListNullable,
    ) {
        $this->isValueNullable = $isValueNullable;
        $this->isList = $isList;
        $this->isListNullable = $isListNullable;
    }

    /**
     * @param list<class-string> $classNames
     */
    public static function create(string $name, array $classNames): self
    {
        return new self($name, $classNames, false, false, false);
    }

    /**
     * @return UnionTypeReferencePayload
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'classNames' => $this->classNames,
            'isValueNullable' => $this->isValueNullable(),
            'isList' => $this->isList(),
            'isListNullable' => $this->isListNullable(),
        ];
    }

    /**
     * @param UnionTypeReferencePayload $payload
     */
    public static function fromArray(array $payload): UnionTypeReference
    {
        return new self(
            $payload['name'],
            $payload['classNames'],
            $payload['isValueNullable'],
            $payload['isList'],
            $payload['isListNullable'],
        );
    }

    public function equals(TypeReference $reference): bool
    {
        return $reference instanceof self
            && $reference->name === $this->name
            && $reference->classNames === $this->classNames
            && $reference->isValueNullable === $this->isValueNullable
            && $reference->isList === $this->isList
            && $reference->isListNullable === $this->isListNullable;
    }
}
