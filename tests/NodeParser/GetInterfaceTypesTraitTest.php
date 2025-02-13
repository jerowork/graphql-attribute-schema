<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use Jerowork\GraphqlAttributeSchema\NodeParser\GetInterfaceTypesTrait;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\AbstractTestInterfaceType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\TestOtherInterfaceType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\TestSubInterfaceType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestCascadingInterfaceType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @internal
 */
final class GetInterfaceTypesTraitTest extends TestCase
{
    #[Test]
    public function itShouldGetCascadingInterfaces(): void
    {
        $trait = new class {
            use GetInterfaceTypesTrait;
        };

        $interfaces = $trait->getInterfaceTypes(new ReflectionClass(TestCascadingInterfaceType::class));

        self::assertSame([
            TestOtherInterfaceType::class,
            TestSubInterfaceType::class,
            AbstractTestInterfaceType::class,
        ], $interfaces);
    }
}
