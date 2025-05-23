<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node\TypeReference;

/**
 * @phpstan-type ConnectionReferencePayload array{
 *     className: class-string,
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
        bool $isValueNullable,
    ) {
        $this->isValueNullable = $isValueNullable;
    }

    /**
     * @param class-string $className
     */
    public static function create(string $className): self
    {
        return new self($className, false);
    }

    /**
     * @return ConnectionReferencePayload
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
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
            $payload['isValueNullable'],
        );
    }

    public function equals(TypeReference $reference): bool
    {
        return $reference instanceof self
            && $reference->className === $this->className
            && $reference->isValueNullable === $this->isValueNullable;
    }
}
