<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Type as AttributeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Class\TypeClassNodeParser;
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
                    new ArgNodeParser(),
                ),
            ),
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
        $node = $this->parser->parse(new ReflectionClass(TestType::class));

        self::assertEquals(new TypeNode(
            TestType::class,
            'Test',
            'Test Type',
            [
                new FieldNode(
                    ScalarNodeType::create('string')->setNullableValue(),
                    'typeId',
                    null,
                    [],
                    FieldNodeType::Property,
                    null,
                    'id',
                    null,
                ),
                new FieldNode(
                    ObjectNodeType::create(DateTimeImmutable::class),
                    'date',
                    null,
                    [],
                    FieldNodeType::Property,
                    null,
                    'date',
                    null,
                ),
                new FieldNode(
                    ScalarNodeType::create('string')->setNullableValue(),
                    'flow',
                    null,
                    [],
                    FieldNodeType::Method,
                    'flow',
                    null,
                    null,
                ),
                new FieldNode(
                    ScalarNodeType::create('string'),
                    'status',
                    null,
                    [],
                    FieldNodeType::Method,
                    'getStatus',
                    null,
                    null,
                ),
            ],
        ), $node);
    }
}
