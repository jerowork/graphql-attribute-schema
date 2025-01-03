<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\IsRequiredTrait;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;

/**
 * @internal
 */
final class IsRequiredTraitTest extends TestCase
{
    #[Test]
    public function itShouldReturnIfRequiredFromAttribute(): void
    {
        $trait = new class {
            use IsRequiredTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type1 = $parameters[0]->getType();
        $type2 = $parameters[1]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type1);
        self::assertTrue($trait->isRequired($type1, new Mutation()));
        self::assertInstanceOf(ReflectionNamedType::class, $type2);
        self::assertFalse($trait->isRequired($type2, new Mutation()));
    }

    #[Test]
    public function itShouldReturnIfRequiredFromType(): void
    {
        $trait = new class {
            use IsRequiredTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type1 = $parameters[0]->getType();
        $type2 = $parameters[1]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type1);
        self::assertFalse($trait->isRequired($type1, new Mutation(type: ScalarType::Int, isRequired: false)));
        self::assertInstanceOf(ReflectionNamedType::class, $type2);
        self::assertTrue($trait->isRequired($type2, new Mutation(type: ScalarType::Int, isRequired: true)));
    }

    #[Test]
    public function itShouldReturnIfRequiredFromTypeWhenTypeIsNotSetInAttribute(): void
    {
        $trait = new class {
            use IsRequiredTrait;
        };

        $class = new ReflectionClass(TestMutation::class);
        $methods = $class->getMethod('__invoke');
        $parameters = $methods->getParameters();
        $type1 = $parameters[0]->getType();
        $type2 = $parameters[1]->getType();

        self::assertInstanceOf(ReflectionNamedType::class, $type1);
        self::assertTrue($trait->isRequired($type1, new Mutation(isRequired: false)));
        self::assertInstanceOf(ReflectionNamedType::class, $type2);
        self::assertFalse($trait->isRequired($type2, new Mutation(isRequired: true)));
    }
}
