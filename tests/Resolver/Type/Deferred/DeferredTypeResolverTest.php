<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type\Deferred;

use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeRegistryFactory;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeResolver;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader\TestDeferredType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader\TestTypeLoader;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class DeferredTypeResolverTest extends TestCase
{
    private DeferredTypeResolver $resolver;
    private TestTypeLoader $loader;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new DeferredTypeResolver(
            $container = new TestContainer(),
            new DeferredTypeRegistryFactory(),
        );

        $container->set(TestTypeLoader::class, $this->loader = new TestTypeLoader());
    }

    #[Test]
    public function itShouldResolveDeferredType(): void
    {
        $deferred = $this->resolver->resolve(TestTypeLoader::class, '1');
        $deferred::runQueue();

        self::assertSame(1, $this->loader->isTimesCalled);
        self::assertEquals(new TestDeferredType('1'), $deferred->result);
    }

    #[Test]
    public function itShouldResolveListOfDeferredTypes(): void
    {
        $deferred = $this->resolver->resolve(TestTypeLoader::class, ['1', '2']);
        $deferred::runQueue();

        self::assertSame(1, $this->loader->isTimesCalled);
        self::assertEquals([new TestDeferredType('1'), new TestDeferredType('2')], $deferred->result);
    }
}
