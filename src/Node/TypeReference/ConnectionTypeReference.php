<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\TypeReference;

/**
 * @phpstan-type ConnectionReferencePayload array{
 *     className: class-string,
 *     first: int,
 *     isValueNullable: bool
 * }
 *
 * @internal
 */
final class ConnectionTypeReference implements TypeReference
{
    use TypeReferenceTrait;

    /**
     * @param class-string $className
     */
    public function __construct(
        public readonly string $className,
        public readonly int $first,
        bool $isValueNullable,
    ) {
        $this->isValueNullable = $isValueNullable;
    }

    /**
     * @param class-string $className
     */
    public static function create(string $className, int $first): self
    {
        return new self($className, $first, false);
    }

    /**
     * @return ConnectionReferencePayload
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
            'first' => $this->first,
            'isValueNullable' => $this->isValueNullable(),
        ];
    }

    /**
     * @param ConnectionReferencePayload $payload
     */
    public static function fromArray(array $payload): ConnectionTypeReference
    {
        return new self(
            $payload['className'],
            $payload['first'],
            $payload['isValueNullable'],
        );
    }

    public function equals(TypeReference $reference): bool
    {
        return $reference instanceof self
            && $reference->className === $this->className
            && $reference->first === $this->first
            && $reference->isValueNullable === $this->isValueNullable;
    }
}
