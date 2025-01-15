<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Reference;

/**
 * @phpstan-type ObjectNodeTypePayload array{
 *     className: class-string,
 *     isValueNullable: bool,
 *     isList: bool,
 *     isListNullable: bool
 * }
 */
final class ObjectReference implements ListableReference
{
    use ReferenceTrait;
    use ListableReferenceTrait;

    /**
     * @param class-string $className
     */
    public function __construct(
        public readonly string $className,
        bool $isValueNullable,
        bool $isList,
        bool $isListNullable,
    ) {
        $this->isValueNullable = $isValueNullable;
        $this->isList = $isList;
        $this->isListNullable = $isListNullable;
    }

    /**
     * @param class-string $className
     */
    public static function create(string $className): self
    {
        return new self($className, false, false, false);
    }

    /**
     * @return ObjectNodeTypePayload
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
            'isValueNullable' => $this->isValueNullable(),
            'isList' => $this->isList(),
            'isListNullable' => $this->isListNullable(),
        ];
    }

    /**
     * @param ObjectNodeTypePayload $payload
     */
    public static function fromArray(array $payload): ObjectReference
    {
        return new self(
            $payload['className'],
            $payload['isValueNullable'],
            $payload['isList'],
            $payload['isListNullable'],
        );
    }

    public function equals(Reference $reference): bool
    {
        return $reference instanceof self
            && $reference->className === $this->className
            && $reference->isValueNullable === $this->isValueNullable
            && $reference->isList === $this->isList
            && $reference->isListNullable === $this->isListNullable;
    }
}
