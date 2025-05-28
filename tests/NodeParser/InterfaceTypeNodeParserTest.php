<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\InterfaceType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\InterfaceTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\InterfaceTypeNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\AbstractTestInterfaceType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\TestInterfaceType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @internal
 */
final class InterfaceTypeNodeParserTest extends TestCase
{
    private InterfaceTypeNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new InterfaceTypeNodeParser(
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
    public function itShouldSupportInterfaceTypeOnly(): void
    {
        $nodes = iterator_to_array($this->parser->parse(Mutation::class, new ReflectionClass(TestInterfaceType::class), null));

        self::assertEmpty($nodes);
    }

    #[Test]
    public function itShouldParseInterfaceType(): void
    {
        $nodes = iterator_to_array($this->parser->parse(InterfaceType::class, new ReflectionClass(TestInterfaceType::class), null));

        self::assertEquals([new InterfaceTypeNode(
            TestInterfaceType::class,
            'TestInterface',
            null,
            [
                new FieldNode(
                    ScalarTypeReference::create('int'),
                    'ID',
                    null,
                    [],
                    FieldNodeType::Method,
                    'getId',
                    null,
                    null,
                    null,
                ),
                new FieldNode(
                    ScalarTypeReference::create('string')->setNullableValue(),
                    'name',
                    null,
                    [],
                    FieldNodeType::Method,
                    'getName',
                    null,
                    null,
                    null,
                ),
            ],
            new CursorNode(
                ScalarTypeReference::create('string')->setNullableValue(),
                FieldNodeType::Method,
                'cursor',
                null,
            ),
            [],
        )], $nodes);
    }

    #[Test]
    public function itShouldParseAbstractClassAsInterfaceType(): void
    {
        $nodes = iterator_to_array($this->parser->parse(
            InterfaceType::class,
            new ReflectionClass(AbstractTestInterfaceType::class),
            null,
        ));

        self::assertEquals([new InterfaceTypeNode(
            AbstractTestInterfaceType::class,
            'TestInterface',
            'A description',
            [
                new FieldNode(
                    ScalarTypeReference::create('string'),
                    'constructId',
                    null,
                    [],
                    FieldNodeType::Property,
                    null,
                    'constructId',
                    null,
                    null,
                ),
                new FieldNode(
                    ScalarTypeReference::create('string')->setNullableValue(),
                    'status',
                    null,
                    [],
                    FieldNodeType::Method,
                    'getStatus',
                    null,
                    null,
                    null,
                ),
                new FieldNode(
                    ScalarTypeReference::create('float'),
                    'value',
                    null,
                    [],
                    FieldNodeType::Method,
                    'getValue',
                    null,
                    null,
                    null,
                ),
            ],
            new CursorNode(
                ScalarTypeReference::create('string')->setNullableValue(),
                FieldNodeType::Method,
                'getStatus',
                null,
            ),
            [],
        )], $nodes);
    }
}
