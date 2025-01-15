<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInvalidConnectionMethodType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInvalidConnectionPropertyType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInvalidType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;
use ReflectionClass;
use DateTimeImmutable;

/**
 * @internal
 */
final class ClassFieldNodesParserTest extends TestCase
{
    private ClassFieldNodesParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new ClassFieldNodesParser(
            new MethodArgumentNodesParser(
                new AutowireNodeParser(),
                new EdgeArgsNodeParser(),
                new ArgNodeParser(),
            ),
        );
    }

    #[Test]
    public function itShouldGuardInvalidReturnType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid return type');

        $this->parser->parse(new ReflectionClass(TestInvalidType::class));
    }

    #[Test]
    public function itShouldGuardPropertyConnectionType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid property type for connection');

        $this->parser->parse(new ReflectionClass(TestInvalidConnectionPropertyType::class));
    }

    #[Test]
    public function itShouldGuardMethodConnectionReturnType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid return type for connection');

        $this->parser->parse(new ReflectionClass(TestInvalidConnectionMethodType::class));
    }

    #[Test]
    public function itShouldParseFields(): void
    {
        $fields = $this->parser->parse(new ReflectionClass(TestType::class));

        self::assertEquals([
            new FieldNode(
                ScalarReference::create('string')->setNullableValue(),
                'typeId',
                null,
                [],
                FieldNodeType::Property,
                null,
                'id',
                null,
            ),
            new FieldNode(
                ObjectReference::create(DateTimeImmutable::class),
                'date',
                null,
                [],
                FieldNodeType::Property,
                null,
                'date',
                null,
            ),
            new FieldNode(
                ScalarReference::create('string')->setNullableValue(),
                'flow',
                null,
                [],
                FieldNodeType::Method,
                'flow',
                null,
                null,
            ),
            new FieldNode(
                ScalarReference::create('string'),
                'status',
                null,
                [],
                FieldNodeType::Method,
                'getStatus',
                null,
                null,
            ),
        ], $fields);
    }
}
