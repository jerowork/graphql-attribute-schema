<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInvalidScalarCursorType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInvalidTypeWithMultipleCursors;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestMethodCursorType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestPropertyCursorType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\CursorNodeParser;
use PHPUnit\Framework\Attributes\Test;
use Override;
use ReflectionClass;

/**
 * @internal
 */
final class CursorNodeParserTest extends TestCase
{
    private CursorNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new CursorNodeParser();
    }

    #[Test]
    public function itShouldGuardMultipleCursorDefinition(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Multiple cursors found for class');

        $this->parser->parse(new ReflectionClass(TestInvalidTypeWithMultipleCursors::class));
    }

    #[Test]
    public function itShouldGuardInvalidScalarType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid property type for connection for class');

        $this->parser->parse(new ReflectionClass(TestInvalidScalarCursorType::class));
    }

    #[Test]
    public function itShouldReturnNoCursor(): void
    {
        self::assertNull($this->parser->parse(new ReflectionClass(TestType::class)));
    }

    #[Test]
    public function itShouldParsePropertySetCursor(): void
    {
        $cursorNode = $this->parser->parse(new ReflectionClass(TestPropertyCursorType::class));

        self::assertEquals(new CursorNode(
            ScalarReference::create('string'),
            FieldNodeType::Property,
            null,
            'cursor',
        ), $cursorNode);
    }

    #[Test]
    public function itShouldParseMethodSetCursor(): void
    {
        $cursorNode = $this->parser->parse(new ReflectionClass(TestMethodCursorType::class));

        self::assertEquals(new CursorNode(
            ScalarReference::create('string'),
            FieldNodeType::Method,
            'getCursor',
            null,
        ), $cursorNode);
    }
}
