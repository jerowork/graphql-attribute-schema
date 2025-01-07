<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
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
        self::assertTrue($trait->getType($type, new Mutation())?->equals(Type::createScalar('string')->setNullableValue()));
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
        self::assertTrue($trait->getType($type, new Mutation())?->equals(Type::createObject(DateTimeImmutable::class)));
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

        $nodeType = $trait->getType($type, new Mutation(type: ScalarType::Int));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(Type::createScalar('int')));
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

        $nodeType = $trait->getType($type, new Mutation(type: DateTime::class));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(Type::createObject(DateTime::class)));
    }

    #[Test]
    public function itShouldReturnNullableScalarFromAttribute(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getType($type, new Mutation(type: new NullableType(ScalarType::Int)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(Type::createScalar('int')->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnNullableObjectFromAttribute(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getType($type, new Mutation(type: new NullableType(DateTime::class)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(Type::createObject(DateTime::class)->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnListOfScalarsFromAttribute(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getType($type, new Mutation(type: new ListType(ScalarType::Int)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(Type::createScalar('int')->setList()));
    }

    #[Test]
    public function itShouldReturnListOfNullableScalarsFromAttribute(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getType($type, new Mutation(type: new ListType(new NullableType(ScalarType::Int))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(Type::createScalar('int')->setList()->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnNullableListOfScalarsFromAttribute(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getType($type, new Mutation(type: new NullableType(new ListType(ScalarType::Int))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(Type::createScalar('int')->setList()->setNullableList()));
    }

    #[Test]
    public function itShouldReturnNullableListOfNullableScalarsFromAttribute(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getType($type, new Mutation(type: new NullableType(new ListType(new NullableType(ScalarType::Int)))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(Type::createScalar('int')->setNullableValue()->setList()->setNullableList()));
    }

    #[Test]
    public function itShouldReturnNullWhenObjectIsNull(): void
    {
        $trait = new class {
            use GetTypeTrait;
        };

        self::assertNull($trait->getType(null, new Mutation()));
    }
}
