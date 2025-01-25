<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

/**
 * @internal
 */
interface ArraySerializable
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self;
}
