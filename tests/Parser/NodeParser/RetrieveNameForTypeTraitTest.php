<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestAnother;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\RetrieveNameForTypeTrait;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;

/**
 * @internal
 */
final class RetrieveNameForTypeTraitTest extends TestCase
{
    #[Test]
    public function itShouldGuardInvalidName(): void
    {
        $trait = new class {
            use RetrieveNameForTypeTrait;
        };

        self::expectException(ParseException::class);

        $trait->retrieveNameForType(
            new ReflectionClass(TestType::class),
            new Mutation('customName'),
        );
    }

    #[Test]
    public function itShouldRetrieveNameFromAttribute(): void
    {
        $trait = new class {
            use RetrieveNameForTypeTrait;
        };

        self::assertSame('CustomName', $trait->retrieveNameForType(
            new ReflectionClass(TestType::class),
            new Type('CustomName'),
        ));
    }

    #[Test]
    public function itShouldRetrieveNameFromClass(): void
    {
        $trait = new class {
            use RetrieveNameForTypeTrait;
        };

        self::assertSame('TestAnother', $trait->retrieveNameForType(
            new ReflectionClass(TestAnother::class),
            new Type(),
        ));
    }

    #[Test]
    public function itShouldRetrieveNameFromClassAndRemoveSuffix(): void
    {
        $trait = new class {
            use RetrieveNameForTypeTrait;
        };

        self::assertSame('Test', $trait->retrieveNameForType(
            new ReflectionClass(TestType::class),
            new Type(),
        ));
    }
}
