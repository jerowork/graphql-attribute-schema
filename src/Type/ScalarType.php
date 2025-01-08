<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Type;

/**
 * @template T of mixed
 */
interface ScalarType
{
    /**
     * @param T $value
     */
    public static function serialize(mixed $value): string;

    /**
     * @return T
     */
    public static function deserialize(string $value): mixed;
}
