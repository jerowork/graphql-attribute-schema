<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Type as AttributeType;
use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeClassNodeParser;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Override;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use DateTimeImmutable;

/**
 * @internal
 */
final class TypeClassNodeParserTest extends TestCase
{
    private TypeClassNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new TypeClassNodeParser(
            new ClassFieldNodesParser(
                new MethodArgumentNodesParser(
                    new AutowireNodeParser(),
                    new EdgeArgsNodeParser(),
                    new ArgNodeParser(),
                ),
            ),
            new CursorNodeParser(),
        );
    }

    #[Test]
    public function itShouldSupportTypeOnly(): void
    {
        self::assertTrue($this->parser->supports(AttributeType::class));
        self::assertFalse($this->parser->supports(Mutation::class));
    }

    #[Test]
    public function itShouldParseType(): void
    {
        $node = $this->parser->parse(new ReflectionClass(TestType::class), null);

        self::assertEquals(new TypeNode(
            TestType::class,
            'Test',
            'Test Type',
            [
                new FieldNode(
                    ScalarTypeReference::create('string')->setNullableValue(),
                    'typeId',
                    null,
                    [],
                    FieldNodeType::Property,
                    null,
                    'id',
                    null,
                ),
                new FieldNode(
                    ObjectTypeReference::create(DateTimeImmutable::class),
                    'date',
                    null,
                    [],
                    FieldNodeType::Property,
                    null,
                    'date',
                    null,
                ),
                new FieldNode(
                    ScalarTypeReference::create('string')->setNullableValue(),
                    'flow',
                    null,
                    [],
                    FieldNodeType::Method,
                    'flow',
                    null,
                    null,
                ),
                new FieldNode(
                    ScalarTypeReference::create('string'),
                    'status',
                    null,
                    [],
                    FieldNodeType::Method,
                    'getStatus',
                    null,
                    null,
                ),
            ],
            new CursorNode(
                ScalarTypeReference::create('string')->setNullableValue(),
                FieldNodeType::Method,
                'flow',
                null,
            ),
        ), $node);
    }
}
