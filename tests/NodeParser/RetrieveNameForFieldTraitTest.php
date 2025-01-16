<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\NodeParser\RetrieveNameForFieldTrait;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;

/**
 * @internal
 */
final class RetrieveNameForFieldTraitTest extends TestCase
{
    #[Test]
    public function itShouldRetrieveNameFromAttribute(): void
    {
        $trait = new class {
            use RetrieveNameForFieldTrait;
        };

        $class = new ReflectionClass(TestType::class);
        $method = $class->getMethods()[0];

        self::assertSame('customName', $trait->retrieveNameForField($method, new Type('customName')));
    }

    #[Test]
    public function itShouldRetrieveNameFromMethod(): void
    {
        $trait = new class {
            use RetrieveNameForFieldTrait;
        };

        $class = new ReflectionClass(TestType::class);
        $method = $class->getMethods()[1];
        self::assertSame('flow', $trait->retrieveNameForField($method, new Type()));
    }

    #[Test]
    public function itShouldRetrieveNameFromMethodAndRemoveGetPrefix(): void
    {
        $trait = new class {
            use RetrieveNameForFieldTrait;
        };

        $class = new ReflectionClass(TestType::class);
        $method = $class->getMethods()[2];
        self::assertSame('status', $trait->retrieveNameForField($method, new Type()));
    }
}
