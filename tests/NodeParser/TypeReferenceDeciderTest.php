<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use DateTime;
use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\NullableType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestConnectionMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestNullableConnectionMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;

/**
 * @internal
 */
final class TypeReferenceDeciderTest extends TestCase
{
    #[Test]
    public function itShouldReturnScalar(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($decider->getTypeReference($type, new Mutation())?->equals(ScalarTypeReference::create('string')->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnObject(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($decider->getTypeReference($type, new Mutation())?->equals(ObjectTypeReference::create(DateTimeImmutable::class)));
    }

    #[Test]
    public function itShouldReturnScalarFromAttribute(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $decider->getTypeReference($type, new Mutation(type: ScalarType::Int));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')));
    }

    #[Test]
    public function itShouldReturnObjectFromAttribute(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        $nodeType = $decider->getTypeReference($type, new Mutation(type: DateTime::class));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ObjectTypeReference::create(DateTime::class)));
    }

    #[Test]
    public function itShouldReturnNullableScalarFromAttribute(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $decider->getTypeReference($type, new Mutation(type: new NullableType(ScalarType::Int)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnNullableObjectFromAttribute(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $decider->getTypeReference($type, new Mutation(type: new NullableType(DateTime::class)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ObjectTypeReference::create(DateTime::class)->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnListOfScalarsFromAttribute(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $decider->getTypeReference($type, new Mutation(type: new ListType(ScalarType::Int)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')->setList()));
    }

    #[Test]
    public function itShouldReturnListOfNullableScalarsFromAttribute(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $decider->getTypeReference($type, new Mutation(type: new ListType(new NullableType(ScalarType::Int))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')->setList()->setNullableValue()));
    }

    #[Test]
    public function itShouldReturnNullableListOfScalarsFromAttribute(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $decider->getTypeReference($type, new Mutation(type: new NullableType(new ListType(ScalarType::Int))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')->setList()->setNullableList()));
    }

    #[Test]
    public function itShouldReturnNullableListOfNullableScalarsFromAttribute(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('testMutation');
        $parameters = $methods->getParameters();
        $type = $parameters[1]->getType();

        $nodeType = $decider->getTypeReference($type, new Mutation(type: new NullableType(new ListType(new NullableType(ScalarType::Int)))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($nodeType?->equals(ScalarTypeReference::create('int')->setNullableValue()->setList()->setNullableList()));
    }

    #[Test]
    public function itShouldReturnNullWhenObjectIsNull(): void
    {
        $decider = new TypeReferenceDecider();

        self::assertNull($decider->getTypeReference(null, new Mutation()));
    }

    #[Test]
    public function itShouldReturnConnectionFromAttribute(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestConnectionMutation::class);
        $methods = $class->getMethod('mutate');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        $reference = $decider->getTypeReference($type, new Mutation(type: new ConnectionType(TestType::class, 12)));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($reference?->equals(ConnectionTypeReference::create(TestType::class, 12)));
    }

    #[Test]
    public function itShouldReturnNullableConnectionFromAttribute(): void
    {
        $decider = new TypeReferenceDecider();

        $class = new ReflectionClass(TestNullableConnectionMutation::class);
        $methods = $class->getMethod('mutate');
        $parameters = $methods->getParameters();
        $type = $parameters[0]->getType();

        $reference = $decider->getTypeReference($type, new Mutation(type: new NullableType(new ConnectionType(TestType::class, 12))));

        self::assertInstanceOf(ReflectionNamedType::class, $type);
        self::assertTrue($reference?->equals(ConnectionTypeReference::create(TestType::class, 12)->setNullableValue()));
    }
}
