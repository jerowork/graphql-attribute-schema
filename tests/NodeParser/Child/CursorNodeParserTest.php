<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInvalidScalarCursorType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInvalidTypeWithMultipleCursors;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestMethodCursorType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestPropertyCursorType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestResolvableType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
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

        $this->parser = new CursorNodeParser(new TypeReferenceDecider());
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
        self::assertNull($this->parser->parse(new ReflectionClass(TestResolvableType::class)));
    }

    #[Test]
    public function itShouldParsePropertySetCursor(): void
    {
        $cursorNode = $this->parser->parse(new ReflectionClass(TestPropertyCursorType::class));

        self::assertEquals(new CursorNode(
            ScalarTypeReference::create('string'),
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
            ScalarTypeReference::create('string'),
            FieldNodeType::Method,
            'getCursor',
            null,
        ), $cursorNode);
    }
}
