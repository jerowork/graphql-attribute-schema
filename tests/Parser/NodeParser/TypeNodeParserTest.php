<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\TypeNodeParser;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Override;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use DateTimeImmutable;

/**
 * @internal
 */
final class TypeNodeParserTest extends TestCase
{
    private TypeNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new TypeNodeParser(
            new ClassFieldNodesParser(
                new MethodArgNodesParser(),
            ),
        );
    }

    #[Test]
    public function itShouldSupportTypeOnly(): void
    {
        self::assertTrue($this->parser->supports(Type::class));
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
                    null,
                    'string',
                    'typeId',
                    null,
                    false,
                    [],
                    FieldNodeType::Property,
                    null,
                    'id',
                ),
                new FieldNode(
                    DateTimeImmutable::class,
                    null,
                    'date',
                    null,
                    true,
                    [],
                    FieldNodeType::Property,
                    null,
                    'date',
                ),
                new FieldNode(
                    null,
                    'string',
                    'flow',
                    null,
                    false,
                    [],
                    FieldNodeType::Method,
                    'flow',
                    null,
                ),
                new FieldNode(
                    null,
                    'string',
                    'status',
                    null,
                    true,
                    [],
                    FieldNodeType::Method,
                    'getStatus',
                    null,
                ),
            ],
        ), $node);
    }
}
