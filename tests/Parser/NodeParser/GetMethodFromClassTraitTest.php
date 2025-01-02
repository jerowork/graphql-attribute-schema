<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetMethodFromClassTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestInvalidMutationWithNoMethods;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestInvalidMutationWithTooManyMethods;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @internal
 */
final class GetMethodFromClassTraitTest extends TestCase
{
    #[Test]
    public function itShouldGuardThatClassHasMethodsDefined(): void
    {
        $trait = new class {
            use GetMethodFromClassTrait;
        };

        self::expectException(ParseException::class);
        self::expectExceptionMessage('Missing method in class');

        $trait->getMethodFromClass(new ReflectionClass(TestInvalidMutationWithNoMethods::class));
    }

    #[Test]
    public function itShouldGuardThatClassHasOnlyOneMethodDefined(): void
    {
        $trait = new class {
            use GetMethodFromClassTrait;
        };

        self::expectException(ParseException::class);
        self::expectExceptionMessage('Too many methods in class');

        $trait->getMethodFromClass(new ReflectionClass(TestInvalidMutationWithTooManyMethods::class));
    }

    #[Test]
    public function itShouldGetMethodFromClass(): void
    {
        $trait = new class {
            use GetMethodFromClassTrait;
        };

        $method = $trait->getMethodFromClass(new ReflectionClass(TestMutation::class));

        self::assertSame('__invoke', $method->getName());
    }
}
