<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred;

use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredTypeLoader;

/**
 * @internal
 */
final class DeferredTypeRegistryFactory
{
    /**
     * @var array<class-string<DeferredTypeLoader>, DeferredTypeRegistry>
     */
    private array $registries = [];

    /**
     * @param class-string<DeferredTypeLoader> $typeLoaderClass
     */
    public function createForTypeLoader(string $typeLoaderClass): DeferredTypeRegistry
    {
        if (!isset($this->registries[$typeLoaderClass])) {
            $this->registries[$typeLoaderClass] = new DeferredTypeRegistry();
        }

        return $this->registries[$typeLoaderClass];
    }
}
