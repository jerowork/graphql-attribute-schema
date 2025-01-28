<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\ChainedNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ScalarNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\EnumNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\InputTypeNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\MutationNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\QueryNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Parser;
use Jerowork\GraphqlAttributeSchema\SchemaBuilderFactory;
use Jerowork\GraphqlAttributeSchema\SchemaBuildException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Mutation\FoobarMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\FoobarQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestQuery;
use Jerowork\GraphqlAttributeSchema\Type\DateTimeType;
use Jerowork\GraphqlAttributeSchema\Util\Finder\Native\NativeFinder;
use Jerowork\GraphqlAttributeSchema\Util\Reflector\Roave\RoaveReflector;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\SchemaBuilder;
use Override;

/**
 * @internal
 */
final class SchemaBuilderTest extends TestCase
{
    private TestContainer $container;
    private SchemaBuilder $schemaBuilder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->schemaBuilder = SchemaBuilderFactory::create($this->container = new TestContainer());
    }

    #[Test]
    public function itShouldGuardMissingQueries(): void
    {
        self::expectException(SchemaBuildException::class);
        self::expectExceptionMessage('No queries defined');

        $this->schemaBuilder->build(new Ast());
    }

    #[Test]
    public function itShouldGuardMissingMutations(): void
    {
        $this->container->set(TestQuery::class, new TestQuery());

        self::expectException(SchemaBuildException::class);
        self::expectExceptionMessage('No mutations defined');

        $this->schemaBuilder->build(new Ast(
            new QueryNode(
                TestQuery::class,
                'testQuery',
                null,
                [],
                ScalarTypeReference::create('string'),
                '__invoke',
                null,
            ),
        ));
    }

    #[Test]
    public function itShouldBuildSchema(): void
    {
        $this->container->set(FoobarMutation::class, new FoobarMutation());
        $this->container->set(FoobarQuery::class, new FoobarQuery());

        $parser = new Parser(
            new NativeFinder(),
            new RoaveReflector(),
            new ChainedNodeParser([
                new EnumNodeParser(),
                new InputTypeNodeParser($classFieldNodesParser = new ClassFieldsNodeParser(
                    $typeReferenceDecider = new TypeReferenceDecider(),
                    $methodArgsNodeParser = new MethodArgumentsNodeParser(
                        new AutowireNodeParser(),
                        new EdgeArgsNodeParser(),
                        new ArgNodeParser($typeReferenceDecider),
                    ),
                )),
                new TypeNodeParser($classFieldNodesParser, new CursorNodeParser($typeReferenceDecider)),
                new ScalarNodeParser(),
                new MutationNodeParser($typeReferenceDecider, $methodArgsNodeParser),
                new QueryNodeParser($typeReferenceDecider, $methodArgsNodeParser),
            ]),
            [
                DateTimeType::class,
            ],
        );

        $ast = $parser->parse(__DIR__ . '/Doubles/FullFeatured');

        $schema = $this->schemaBuilder->build($ast);

        self::assertEquals(new ObjectType([
            'name' => 'Query',
            'fields' => [
                [
                    'name' => 'getFoobar',
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(new InterfaceType([
                        'name' => 'User',
                        'description' => null,
                        'fields' => [
                            [
                                'name' => 'userId',
                                'type' => Type::nonNull(Type::int()),
                                'description' => null,
                                'args' => [],
                                'resolve' => fn() => true,
                            ],
                        ],
                    ])))),
                    'description' => 'Get a Foobar',
                    'args' => [
                        [
                            'name' => 'id',
                            'type' => Type::int(),
                            'description' => null,
                        ],
                        [
                            'name' => 'date',
                            'type' => Type::nonNull(new CustomScalarType([
                                'name' => 'DateTime',
                                'serialize' => fn() => true,
                                'parseValue' => fn() => true,
                                'parseLiteral' => fn() => true,
                                'description' => 'Date and time (ISO-8601)',
                            ])),
                            'description' => null,
                        ],
                        [
                            'name' => 'values',
                            'type' => Type::nonNull(Type::listOf(Type::nonNull(Type::boolean()))),
                            'description' => 'List of values',
                        ],
                    ],
                    'resolve' => fn() => true,
                ],
            ],
        ]), $schema->getConfig()->getQuery());

        self::assertEquals(new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                [
                    'name' => 'first',
                    'type' => Type::nonNull(new ObjectType([
                        'name' => 'Foobar',
                        'description' => 'A foobar',
                        'fields' => [
                            [
                                'name' => 'foobarId',
                                'type' => Type::nonNull(Type::string()),
                                'description' => 'A foobar ID',
                                'args' => [],
                                'resolve' => fn() => true,
                            ],
                            [
                                'name' => 'status',
                                'type' => new EnumType([
                                    'name' => 'FoobarStatus',
                                    'description' => 'Foobar status',
                                    'values' => [
                                        'open' => [
                                            'value' => 'open',
                                            'description' => null,
                                        ],
                                        'closed' => [
                                            'value' => 'closed',
                                            'description' => null,
                                        ],
                                    ],
                                ]),
                                'description' => null,
                                'args' => [],
                                'resolve' => fn() => true,
                            ],
                            [
                                'name' => 'date',
                                'type' => new CustomScalarType([
                                    'name' => 'DateTime',
                                    'serialize' => fn() => true,
                                    'parseValue' => fn() => true,
                                    'parseLiteral' => fn() => true,
                                    'description' => 'Date and time (ISO-8601)',
                                ]),
                                'description' => 'A foobar date',
                                'args' => [
                                    [
                                        'name' => 'limiting',
                                        'description' => null,
                                        'type' => Type::nonNull(Type::string()),
                                    ],
                                    [
                                        'name' => 'value',
                                        'description' => 'The value',
                                        'type' => Type::int(),
                                    ],
                                ],
                                'resolve' => fn() => true,
                            ],
                        ],
                    ])),
                    'description' => 'Mutate a foobar',
                    'args' => [
                        [
                            'name' => 'input',
                            'type' => Type::nonNull(new InputObjectType([
                                'name' => 'MutateFoobar',
                                'description' => null,
                                'fields' => [
                                    [
                                        'name' => 'id',
                                        'type'=> Type::nonNull(Type::int()),
                                        'args' => [],
                                        'description' => null,
                                    ],
                                    [
                                        'name' => 'value',
                                        'type' => Type::string(),
                                        'args' => [],
                                        'description' => null,
                                    ],
                                    [
                                        'name' => 'baz',
                                        'type' => Type::nonNull(new InputObjectType([
                                            'name' => 'Baz',
                                            'description' => null,
                                            'fields' => [
                                                [
                                                    'name' => 'bazId',
                                                    'type'=> Type::nonNull(Type::string()),
                                                    'description' => 'A baz ID',
                                                    'args' => [],
                                                ],
                                                [
                                                    'name' => 'status',
                                                    'type' => Type::nonNull(new EnumType([
                                                        'name' => 'FoobarStatus',
                                                        'description' => 'Foobar status',
                                                        'values' => [
                                                            'open' => [
                                                                'value' => 'open',
                                                                'description' => null,
                                                            ],
                                                            'closed' => [
                                                                'value' => 'closed',
                                                                'description' => null,
                                                            ],
                                                        ],
                                                    ])),
                                                    'description' => null,
                                                    'args' => [],
                                                ],
                                            ],
                                        ])),
                                        'args' => [],
                                        'description' => null,
                                    ],
                                    [
                                        'name' => 'date',
                                        'type' => new CustomScalarType([
                                            'name' => 'DateTime',
                                            'serialize' => fn() => true,
                                            'parseValue' => fn() => true,
                                            'parseLiteral' => fn() => true,
                                            'description' => 'Date and time (ISO-8601)',
                                        ]),
                                        'args' => [],
                                        'description' => null,
                                    ],
                                ],
                            ])),
                            'description' => null,
                        ],
                    ],
                    'resolve' => fn() => true,
                ],
                [
                    'name' => 'second',
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'Mutate a second foobar',
                    'args' => [
                        [
                            'name' => 'value',
                            'type' => Type::nonNull(Type::string()),
                            'description' => null,
                        ],
                    ],
                    'resolve' => fn() => true,
                ],
            ],
        ]), $schema->getConfig()->getMutation());
    }
}
