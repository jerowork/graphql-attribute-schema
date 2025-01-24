<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestInvalidEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\NodeParser\EnumClassNodeParser;
use Override;
use ReflectionClass;

/**
 * @internal
 */
final class EnumClassNodeParserTest extends TestCase
{
    private EnumClassNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new EnumClassNodeParser();
    }

    #[Test]
    public function itShouldSupportEnumOnly(): void
    {
        self::assertTrue($this->parser->supports(Enum::class));
        self::assertFalse($this->parser->supports(Mutation::class));
    }

    #[Test]
    public function itShouldGuardNonEnumClass(): void
    {
        self::expectException(ParseException::class);

        $this->parser->parse(new ReflectionClass(TestType::class), null);
    }

    #[Test]
    public function itShouldGuardNonBackedEnum(): void
    {
        self::expectException(ParseException::class);

        $this->parser->parse(new ReflectionClass(TestInvalidEnumType::class), null);
    }

    #[Test]
    public function itShouldParseEnum(): void
    {
        $node = $this->parser->parse(new ReflectionClass(TestEnumType::class), null);

        self::assertEquals(new EnumNode(
            TestEnumType::class,
            'TestEnum',
            'Test Enum',
            [
                new EnumValueNode(TestEnumType::A->value, null, null),
                new EnumValueNode(TestEnumType::B->value, null, null),
                new EnumValueNode(TestEnumType::C->value, 'Case C', null),
                new EnumValueNode(TestEnumType::D->value, null, null),
            ],
        ), $node);
    }
}
