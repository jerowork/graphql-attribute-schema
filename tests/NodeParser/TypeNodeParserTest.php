<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestExtendsInterfaceType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInterfaceType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

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
            new ClassFieldsNodeParser(
                $typeReferenceDecider = new TypeReferenceDecider(),
                new MethodArgumentsNodeParser(
                    new AutowireNodeParser(),
                    new EdgeArgsNodeParser(),
                    new ArgNodeParser($typeReferenceDecider),
                ),
            ),
            new CursorNodeParser($typeReferenceDecider),
        );
    }

    #[Test]
    public function itShouldSupportTypeOnly(): void
    {
        $nodes = iterator_to_array($this->parser->parse(Mutation::class, new ReflectionClass(TestType::class), null));

        self::assertEmpty($nodes);
    }

    #[Test]
    public function itShouldParseType(): void
    {
        $nodes = iterator_to_array($this->parser->parse(Type::class, new ReflectionClass(TestType::class), null));

        self::assertEquals([new TypeNode(
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
            [],
        )], $nodes);
    }

    #[Test]
    public function itShouldParseTypeExtendingInterface(): void
    {
        $nodes = iterator_to_array($this->parser->parse(Type::class, new ReflectionClass(TestExtendsInterfaceType::class), null));

        self::assertEquals([new TypeNode(
            TestExtendsInterfaceType::class,
            'TestExtendsInterface',
            'Test Type with extends',
            [
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
                    ScalarTypeReference::create('int'),
                    'ID',
                    null,
                    [],
                    FieldNodeType::Method,
                    'getId',
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
            null,
            [TestInterfaceType::class],
        )], $nodes);
    }
}
