<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

final readonly class Type
{
    /**
     * @param string|class-string $id
     */
    public function __construct(
        public string $id,
        public TypeType $type,
    ) {}

    public static function createScalar(string $name): self
    {
        return new self($name, TypeType::Scalar);
    }

    public static function createObject(string $name): self
    {
        return new self($name, TypeType::Object);
    }

    public function isScalar(): bool
    {
        return $this->type === TypeType::Scalar;
    }

    public function isObject(): bool
    {
        return $this->type === TypeType::Object;
    }

    public function equals(Type $type): bool
    {
        return $this->id === $type->id && $this->type === $type->type;
    }
}
