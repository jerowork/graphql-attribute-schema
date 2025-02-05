<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Node\Child\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\EnumNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestInvalidEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @internal
 */
final class EnumNodeParserTest extends TestCase
{
    private EnumNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new EnumNodeParser();
    }

    #[Test]
    public function itShouldSupportEnumOnly(): void
    {
        $nodes = iterator_to_array($this->parser->parse(Mutation::class, new ReflectionClass(TestEnumType::class), null));

        self::assertEmpty($nodes);
    }

    #[Test]
    public function itShouldGuardNonEnumClass(): void
    {
        self::expectException(ParseException::class);

        iterator_to_array($this->parser->parse(Enum::class, new ReflectionClass(TestType::class), null));
    }

    #[Test]
    public function itShouldGuardNonBackedEnum(): void
    {
        self::expectException(ParseException::class);

        iterator_to_array($this->parser->parse(Enum::class, new ReflectionClass(TestInvalidEnumType::class), null));
    }

    #[Test]
    public function itShouldParseEnum(): void
    {
        $nodes = iterator_to_array($this->parser->parse(Enum::class, new ReflectionClass(TestEnumType::class), null));

        self::assertEquals([new EnumNode(
            TestEnumType::class,
            'TestEnum',
            'Test Enum',
            [
                new EnumValueNode(TestEnumType::A->value, null, null),
                new EnumValueNode(TestEnumType::B->value, null, null),
                new EnumValueNode(TestEnumType::C->value, 'Case C', null),
                new EnumValueNode(TestEnumType::D->value, null, null),
            ],
        )], $nodes);
    }
}
