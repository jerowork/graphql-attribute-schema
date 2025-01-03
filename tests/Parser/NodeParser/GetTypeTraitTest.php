<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetTypeTrait;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use ReflectionNamedType;
use DateTimeImmutable;
use DateTime;

/**
 * @internal
 */
final class GetTypeTraitTest extends TestCase
{
    #[Test]
    public function itShouldReturnScalar(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($trait->getType($type, new Mutation())->equals(Type::createScalar('string')));
    }

    #[Test]
    public function itShouldReturnObject(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($trait->getType($type, new Mutation())->equals(Type::createObject(DateTimeImmutable::class)));
    }

    #[Test]
    public function itShouldReturnScalarFromAttribute(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($trait->getType($type, new Mutation(type: ScalarType::Int))->equals(Type::createScalar('int')));
    }

    #[Test]
    public function itShouldReturnObjectFromAttribute(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($trait->getType($type, new Mutation(type: DateTime::class))->equals(Type::createObject(DateTime::class)));
    }
}
