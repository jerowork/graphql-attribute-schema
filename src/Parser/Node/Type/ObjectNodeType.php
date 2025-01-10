<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Type;

/**
 * @phpstan-type ObjectNodeTypePayload array{
 *     className: class-string,
 *     isValueNullable: bool,
 *     isList: bool,
 *     isListNullable: bool
 * }
 */
final class ObjectNodeType implements ListableNodeType
{
    use NodeTypeTrait;
    use ListableNodeTypeTrait;

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
    public static function fromArray(array $payload): ObjectNodeType
    {
        return new self(
            $payload['className'],
            $payload['isValueNullable'],
            $payload['isList'],
            $payload['isListNullable'],
        );
    }

    public function equals(NodeType $type): bool
    {
        return $type instanceof self
            && $type->className === $this->className
            && $type->isValueNullable === $this->isValueNullable
            && $type->isList === $this->isList
            && $type->isListNullable === $this->isListNullable;
    }
}
