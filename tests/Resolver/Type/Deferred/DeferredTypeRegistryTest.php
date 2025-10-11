<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type\Deferred;

use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeRegistry;
use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class DeferredTypeRegistryTest extends TestCase
{
    #[Test]
    public function itShouldAddReferenceToDefer(): void
    {
        $registry = new DeferredTypeRegistry();

        self::assertSame([], $registry->getDeferredReferences());

        $registry->deferReference('1');
        $registry->deferReference('2');

        self::assertSame(['1', '2'], $registry->getDeferredReferences());
    }

    #[Test]
    public function itShouldLoadDeferredTypes(): void
    {
        $registry = new DeferredTypeRegistry();
        $registry->deferReference('1');
        $registry->deferReference('2');

        $registry->load(
            new DeferredType('1', $type1 = new stdClass()),
            new DeferredType('2', $type2 = new stdClass()),
        );

        self::assertSame([], $registry->getDeferredReferences());

        self::assertSame($type1, $registry->getLoadedType('1'));
        self::assertSame($type2, $registry->getLoadedType('2'));
    }

    #[Test]
    public function itShouldReturnNullWhenTypeNotLoaded(): void
    {
        $registry = new DeferredTypeRegistry();

        self::assertNull($registry->getLoadedType('1'));
    }
}
