<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
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
            new MethodArgNodesParser(),
        );
    }

    #[Test]
    public function itShouldGuardInvalidReturnType(): void
    {
        self::expectException(ParseException::class);

        $this->parser->parse(new ReflectionClass(TestInvalidType::class));
    }

    #[Test]
    public function itShouldParseFields(): void
    {
        $fields = $this->parser->parse(new ReflectionClass(TestType::class));

        self::assertEquals([
            new FieldNode(
                Type::createScalar('string'),
                'typeId',
                null,
                false,
                [],
                FieldNodeType::Property,
                null,
                'id',
            ),
            new FieldNode(
                Type::createObject(DateTimeImmutable::class),
                'date',
                null,
                true,
                [],
                FieldNodeType::Property,
                null,
                'date',
            ),
            new FieldNode(
                Type::createScalar('string'),
                'flow',
                null,
                false,
                [],
                FieldNodeType::Method,
                'flow',
                null,
            ),
            new FieldNode(
                Type::createScalar('string'),
                'status',
                null,
                true,
                [],
                FieldNodeType::Method,
                'getStatus',
                null,
            ),
        ], $fields);
    }
}
