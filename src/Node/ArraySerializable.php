<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

/**
 * @template T of array
 *
 * @internal
 */
interface ArraySerializable
{
    /**
     * @return T
     */
    public function toArray(): array;

    /**
     * @param T $payload
     */
    public static function fromArray(array $payload): self; // @phpstan-ignore-line
}
