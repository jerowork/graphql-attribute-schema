<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @internal
 */
final class GetClassAttributeTraitTest extends TestCase
{
    #[Test]
    public function itShouldGuardMissingAttribute(): void
    {
        $trait = new class {
            use GetAttributeTrait;
        };

        self::expectException(ParseException::class);

        $trait->getAttribute(
            new ReflectionClass(TestType::class),
            Enum::class,
        );
    }

    #[Test]
    public function itShouldGetAttributeFromClass(): void
    {
        $trait = new class {
            use GetAttributeTrait;
        };

        $type = $trait->getAttribute(
            new ReflectionClass(TestInputType::class),
            InputType::class,
        );

        self::assertSame('TestInput', $type->name);
    }
}
