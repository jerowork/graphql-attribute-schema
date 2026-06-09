<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use ArrayAccess;
use ArrayIterator;
use GraphQL\Executor\ScopedContext;
use IteratorAggregate;
use Override;
use Traversable;

/**
 * Per-request GraphQL context.
 *
 * Stores arbitrary values, keyed by class-string for objects (see attachObject/getObject) and by
 * any key through the ArrayAccess interface. As a webonyx ScopedContext, child fields receive a
 * clone, so values attached by a resolver are visible to its descendants without leaking to siblings.
 *
 * @implements ArrayAccess<array-key, mixed>
 * @implements IteratorAggregate<array-key, mixed>
 */
final class Context implements ArrayAccess, IteratorAggregate, ScopedContext
{
    /**
     * @param array<array-key, mixed> $store
     */
    public function __construct(
        private array $store = [],
    ) {}

    public function attachObject(object $object): void
    {
        $this->store[$object::class] = $object;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $fqcn
     *
     * @throws ContextException
     *
     * @return T
     */
    public function getObject(string $fqcn): object
    {
        if (!array_key_exists($fqcn, $this->store)) {
            throw ContextException::missingObject($fqcn);
        }

        $object = $this->store[$fqcn];

        if (!$object instanceof $fqcn) {
            throw ContextException::unexpectedObjectType($fqcn);
        }

        return $object;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $fqcn
     *
     * @throws ContextException
     *
     * @return null|T
     */
    public function maybeGetObject(string $fqcn): ?object
    {
        if (!array_key_exists($fqcn, $this->store)) {
            return null;
        }

        $object = $this->store[$fqcn];

        if (!$object instanceof $fqcn) {
            throw ContextException::unexpectedObjectType($fqcn);
        }

        return $object;
    }

    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->store[$offset]);
    }

    #[Override]
    public function offsetGet(mixed $offset): mixed
    {
        return $this->store[$offset] ?? null;
    }

    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->store[] = $value;

            return;
        }

        $this->store[$offset] = $value;
    }

    #[Override]
    public function offsetUnset(mixed $offset): void
    {
        unset($this->store[$offset]);
    }

    #[Override]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->store);
    }

    #[Override]
    public function clone(): self
    {
        return new self($this->store);
    }
}
