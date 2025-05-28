<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred;

use GraphQL\Deferred;
use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredTypeLoader;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Stringable;

/**
 * @internal
 */
final readonly class DeferredTypeResolver
{
    public function __construct(
        private ContainerInterface $container,
        private DeferredTypeRegistryFactory $deferredTypeRegistryFactory,
    ) {}

    /**
     * @param class-string<DeferredTypeLoader> $typeLoaderClass
     * @param list<int|string|Stringable>|int|string|Stringable $reference
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function resolve(string $typeLoaderClass, array|int|string|Stringable $reference): Deferred
    {
        /** @var DeferredTypeLoader $typeLoader */
        $typeLoader = $this->container->get($typeLoaderClass);

        $registry = $this->deferredTypeRegistryFactory->createForTypeLoader($typeLoaderClass);

        if (is_array($reference)) {
            foreach ($reference as $item) {
                $registry->deferReference($item);
            }
        } else {
            $registry->deferReference($reference);
        }

        return new Deferred(function () use ($reference, $registry, $typeLoader) {
            if (!$registry->isLoaded()) {
                $registry->load(...$typeLoader->load($registry->getDeferredReferences()));
            }

            if (is_array($reference)) {
                $loadedTypes = [];
                foreach ($reference as $item) {
                    $loadedTypes[] = $registry->getLoadedType($item);
                }

                return $loadedTypes;
            }

            return $registry->getLoadedType($reference);
        });
    }
}
