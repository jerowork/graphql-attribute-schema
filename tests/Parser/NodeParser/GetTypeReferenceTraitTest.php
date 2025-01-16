<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetTypeReferenceTrait;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestConnectionMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestNullableConnectionMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use ReflectionNamedType;
use DateTimeImmutable;
use DateTime;

/**
 * @internal
 */
final class GetTypeReferenceTraitTest extends TestCase
{
    #[Test]
    public function itShouldReturnScalar(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($trait->getTypeReference($type, new Mutation())?->equals(ScalarTypeReference::create('string')->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnObject(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($trait->getTypeReference($type, new Mutation())?->equals(ObjectTypeReference::create(DateTimeImmutable::class)));
    }

    #[Test]
    public function itShouldReturnScalarFromAttribute(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getTypeReference($type, new Mutation(type: ScalarType::Int));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')));
    }

    #[Test]
    public function itShouldReturnObjectFromAttribute(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        $nodeType = $trait->getTypeReference($type, new Mutation(type: DateTime::class));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ObjectTypeReference::create(DateTime::class)));
    }

    #[Test]
    public function itShouldReturnNullableScalarFromAttribute(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getTypeReference($type, new Mutation(type: new NullableType(ScalarType::Int)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnNullableObjectFromAttribute(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getTypeReference($type, new Mutation(type: new NullableType(DateTime::class)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ObjectTypeReference::create(DateTime::class)->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnListOfScalarsFromAttribute(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getTypeReference($type, new Mutation(type: new ListType(ScalarType::Int)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')->setList()));
    }

    #[Test]
    public function itShouldReturnListOfNullableScalarsFromAttribute(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getTypeReference($type, new Mutation(type: new ListType(new NullableType(ScalarType::Int))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')->setList()->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnNullableListOfScalarsFromAttribute(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getTypeReference($type, new Mutation(type: new NullableType(new ListType(ScalarType::Int))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')->setList()->setNullableList()));
    }

    #[Test]
    public function itShouldReturnNullableListOfNullableScalarsFromAttribute(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $trait->getTypeReference($type, new Mutation(type: new NullableType(new ListType(new NullableType(ScalarType::Int)))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')->setNullableValue()->setList()->setNullableList()));
    }

    #[Test]
    public function itShouldReturnNullWhenObjectIsNull(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        self::assertNull($trait->getTypeReference(null, new Mutation()));
    }

    #[Test]
    public function it_should_return_connection_from_attribute(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestConnectionMutation::class);
        $methods = $class->getMethod('mutate');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        $reference = $trait->getTypeReference($type, new Mutation(type: new ConnectionType(TestType::class, 12)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($reference?->equals(ConnectionTypeReference::create(TestType::class, 12)));
    }

    #[Test]
    public function it_should_return_nullable_connection_from_attribute(): void
    {
        $trait = new class {
            use GetTypeReferenceTrait;
        };

        $class = new ReflectionClass(TestNullableConnectionMutation::class);
        $methods = $class->getMethod('mutate');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        $reference = $trait->getTypeReference($type, new Mutation(type: new NullableType(new ConnectionType(TestType::class, 12))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($reference?->equals(ConnectionTypeReference::create(TestType::class, 12)->setNullableValue()));
    }
}
