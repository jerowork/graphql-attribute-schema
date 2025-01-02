<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetTypeTrait;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use ReflectionNamedType;

/**
 * @internal
 */
final class GetTypeTraitTest extends TestCase
{
    #[Test]
    public function itShouldReturnNullWhenTypeIsNotANamespacedObject(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertNull($trait->getType($type));
    }

    #[Test]
    public function itShouldReturnTypeName(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertSame('string', $trait->getType($type));
    }
}
