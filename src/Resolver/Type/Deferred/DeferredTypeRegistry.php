<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred;

use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredType;
use RuntimeException;
use Stringable;

/**
 * @internal
 */
final class DeferredTypeRegistry
{
    /**
     * @var list<int|string|Stringable>
     */
    private array $deferredReferences = [];

    /**
     * @var array<string, object>
     */
    private array $loaded = [];

    public function deferReference(int|string|Stringable $reference): void
    {
        $this->deferredReferences[] = $reference;
    }

    /**
     * @return list<int|string|Stringable>
     */
    public function getDeferredReferences(): array
    {
        return $this->deferredReferences;
    }

    public function isLoaded(): bool
    {
        return $this->loaded !== [];
    }

    public function load(DeferredType ...$deferredTypes): void
    {
        foreach ($deferredTypes as $deferredType) {
            $this->loaded[(string) $deferredType->reference] = $deferredType->type;
        }

        $this->deferredReferences = [];
    }

    public function getLoadedType(int|string|Stringable $reference): object
    {
        if (!isset($this->loaded[(string) $reference])) {
            throw new RuntimeException(sprintf('Type with reference %s not loaded', $reference));
        }

        return $this->loaded[(string) $reference];
    }
}
