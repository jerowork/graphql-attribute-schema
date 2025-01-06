<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\EnumNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\InputTypeNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\MutationNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\QueryNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\TypeNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\Parser;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Mutation\FoobarMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarStatusType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input\Baz;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input\MutateFoobarInputType;
use Jerowork\GraphqlAttributeSchema\Util\Finder\Native\NativeFinder;
use Jerowork\GraphqlAttributeSchema\Util\Reflector\Roave\RoaveReflector;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;

/**
 * @internal
 */
final class ParserTest extends TestCase
{
    private Parser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new Parser(
            new NativeFinder(),
            new RoaveReflector(),
            [
                new MutationNodeParser($methodArgsNodeParser = new MethodArgNodesParser()),
                new QueryNodeParser($methodArgsNodeParser),
                new EnumNodeParser(),
                new InputTypeNodeParser($classFieldNodesParser = new ClassFieldNodesParser($methodArgsNodeParser)),
                new TypeNodeParser($classFieldNodesParser),
            ],
        );
    }

    #[Test]
    public function it_should_parse_directory(): void
    {
        $ast = $this->parser->parse(__DIR__ . '/../Doubles/FullFeatured');

        self::assertEquals([
            new MutationNode(
                FoobarMutation::class,
                'foobar',
                'Mutate a foobar',
                [
                    new ArgNode(
                        Type::createObject(MutateFoobarInputType::class),
                        'input',
                        null,
                        true,
                        'input',
                    ),
                ],
                Type::createObject(FoobarType::class),
                true,
                '__invoke',
            ),
        ], $ast->getNodesByNodeType(MutationNode::class));

        self::assertEquals([], $ast->getNodesByNodeType(QueryNode::class));

        self::assertEquals([
            new InputTypeNode(
                Baz::class,
                'Baz',
                null,
                [
                    new FieldNode(
                        Type::createScalar('string'),
                        'bazId',
                        'A baz ID',
                        true,
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                    ),
                    new FieldNode(
                        Type::createObject(FoobarStatusType::class),
                        'status',
                        null,
                        true,
                        [],
                        FieldNodeType::Property,
                        null,
                        'status',
                    ),
                ],
            ),
            new InputTypeNode(
                MutateFoobarInputType::class,
                'MutateFoobar',
                null,
                [
                    new FieldNode(
                        Type::createScalar('int'),
                        'id',
                        null,
                        true,
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                    ),
                    new FieldNode(
                        Type::createScalar('string'),
                        'value',
                        null,
                        false,
                        [],
                        FieldNodeType::Property,
                        null,
                        'value',
                    ),
                    new FieldNode(
                        Type::createObject(Baz::class),
                        'baz',
                        null,
                        true,
                        [],
                        FieldNodeType::Property,
                        null,
                        'baz',
                    ),
                    new FieldNode(
                        Type::createObject(DateTimeImmutable::class),
                        'date',
                        null,
                        false,
                        [],
                        FieldNodeType::Property,
                        null,
                        'date',
                    ),
                ],
            ),
        ], $ast->getNodesByNodeType(InputTypeNode::class));

        self::assertEquals([
            new TypeNode(
                FoobarType::class,
                'Foobar',
                'A foobar',
                [
                    new FieldNode(
                        Type::createScalar('string'),
                        'foobarId',
                        'A foobar ID',
                        true,
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                    ),
                    new FieldNode(
                        Type::createObject(FoobarStatusType::class),
                        'status',
                        null,
                        false,
                        [],
                        FieldNodeType::Property,
                        null,
                        'status',
                    ),
                    new FieldNode(
                        Type::createObject(DateTimeImmutable::class),
                        'date',
                        'A foobar date',
                        false,
                        [
                            new ArgNode(
                                Type::createScalar('string'),
                                'limiting',
                                null,
                                true,
                                'limit',
                            ),
                            new ArgNode(
                                Type::createScalar('int'),
                                'value',
                                'The value',
                                false,
                                'value',
                            ),
                        ],
                        FieldNodeType::Method,
                        'getDate',
                        null,
                    ),
                ],
            ),
        ], $ast->getNodesByNodeType(TypeNode::class));

        self::assertEquals([
            new EnumNode(
                FoobarStatusType::class,
                'FoobarStatus',
                'Foobar status',
                [
                    new EnumValueNode('open', null),
                    new EnumValueNode('closed', null),
                ],
            ),
        ], $ast->getNodesByNodeType(EnumNode::class));
    }
}
