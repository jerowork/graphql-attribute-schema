<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetClassAttributeTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
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
            use GetClassAttributeTrait;
        };

        self::expectException(ParseException::class);

        $trait->getClassAttribute(
            new ReflectionClass(TestType::class),
            Enum::class,
        );
    }

    #[Test]
    public function itShouldGetAttributeFromClass(): void
    {
        $trait = new class {
            use GetClassAttributeTrait;
        };

        $type = $trait->getClassAttribute(
            new ReflectionClass(TestInputType::class),
            InputType::class,
        );

        self::assertSame('TestInput', $type->name);
    }
}
