<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Type\Connection;

use IteratorAggregate;
use Traversable;
use ArrayIterator;

/**
 * @implements IteratorAggregate<mixed>
 */
final readonly class Connection implements IteratorAggregate
{
    /**
     * @param array<mixed> $nodes
     */
    public function __construct(
        public array $nodes,
        public bool $hasPreviousPage = false,
        public bool $hasNextPage = false,
        public ?string $startCursor = null,
        public ?string $endCursor = null,
    ) {}

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->nodes);
    }
}
