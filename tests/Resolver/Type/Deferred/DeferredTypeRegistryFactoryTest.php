<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type\Deferred;

use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeRegistryFactory;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader\AnotherTestTypeLoader;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader\TestTypeLoader;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class DeferredTypeRegistryFactoryTest extends TestCase
{
    #[Test]
    public function itShouldCreateRegistryForLoader(): void
    {
        $factory = new DeferredTypeRegistryFactory();

        $registry = $factory->createForTypeLoader(TestTypeLoader::class);
        $registry2 = $factory->createForTypeLoader(TestTypeLoader::class);
        $registry3 = $factory->createForTypeLoader(AnotherTestTypeLoader::class);

        self::assertSame($registry, $registry2);
        self::assertNotSame($registry, $registry3);
    }
}
