<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Type;

use DateTimeImmutable;
use DateTimeInterface;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\StringValueNode;
use InvalidArgumentException;
use Jerowork\GraphqlAttributeSchema\Type\DateTimeType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class DateTimeTypeTest extends TestCase
{
    #[Test]
    public function itShouldFailSerializationWhenInputIsNotDatetimeImmutable(): void
    {
        self::expectException(InvalidArgumentException::class);

        $type = new DateTimeType();
        $type->serialize('some-value');
    }

    #[Test]
    public function itShouldSerialize(): void
    {
        $type = new DateTimeType();
        $serialized = $type->serialize(new DateTimeImmutable('2025-06-04 12:00:12'));

        self::assertSame('2025-06-04T12:00:12+00:00', $serialized);
    }

    #[Test]
    public function itShouldFailParseValueWhenInputIsNotString(): void
    {
        self::expectException(InvalidArgumentException::class);

        $type = new DateTimeType();
        $type->parseValue(123);
    }

    #[Test]
    public function itShouldParseValue(): void
    {
        $type = new DateTimeType();
        $parsed = $type->parseValue('2025-06-04T12:00:12+00:00');

        self::assertSame('2025-06-04T12:00:12+00:00', $parsed->format(DateTimeInterface::ATOM));
    }

    #[Test]
    public function itShouldFailParseLiteralWhenInputIsNotString(): void
    {
        self::expectException(InvalidArgumentException::class);

        $type = new DateTimeType();
        $type->parseLiteral(new IntValueNode([]), []);
    }

    #[Test]
    public function itShouldParseLiteral(): void
    {
        $type = new DateTimeType();
        $parsed = $type->parseLiteral(new StringValueNode([
            'value' => '2025-06-04T12:00:12+00:00',
        ]), []);

        self::assertSame('2025-06-04T12:00:12+00:00', $parsed->format(DateTimeInterface::ATOM));
    }
}
