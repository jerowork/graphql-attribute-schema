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
use Jerowork\GraphqlAttributeSchema\Parser;
use Jerowork\GraphqlAttributeSchema\ParserFactory;
use Jerowork\GraphqlAttributeSchema\SchemaBuilder;
use Jerowork\GraphqlAttributeSchema\SchemaBuilderFactory;
use Jerowork\GraphqlAttributeSchema\SchemaBuildException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Mutation\BasicMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\BasicQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\DeprecatedQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithConnectionOutputQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithInputObjectQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithInterfaceOutputQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithListOutputQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithOverwrittenTypeQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestQuery;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SchemaBuilderTest extends TestCase
{
    private TestContainer $container;
    private Parser $parser;
    private SchemaBuilder $schemaBuilder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->schemaBuilder = (new SchemaBuilderFactory())->create($this->container = new TestContainer());
        $this->parser = (new ParserFactory())->create();
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
        $this->container->set(BasicMutation::class, new BasicMutation());
        $this->container->set(BasicQuery::class, new BasicQuery());
        $this->container->set(DeprecatedQuery::class, new DeprecatedQuery());
        $this->container->set(WithConnectionOutputQuery::class, new WithConnectionOutputQuery());
        $this->container->set(WithInputObjectQuery::class, new WithInputObjectQuery());
        $this->container->set(WithInterfaceOutputQuery::class, new WithInterfaceOutputQuery());
        $this->container->set(WithListOutputQuery::class, new WithListOutputQuery());
        $this->container->set(WithOverwrittenTypeQuery::class, new WithOverwrittenTypeQuery());

        $ast = $this->parser->parse(__DIR__ . '/Doubles/FullFeatured');

        $schema = $this->schemaBuilder->build($ast);

        self::assertEquals(new ObjectType([
            'name' => 'Query',
            'fields' => [
                [
                    'name' => 'basicName',
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'A description',
                    'args' => [
                        [
                            'name' => 'id',
                            'type' => Type::nonNull(Type::int()),
                            'description' => null,
                        ],
                        [
                            'name' => 'name',
                            'type' => Type::string(),
                            'description' => null,
                        ],
                        [
                            'name' => 'isTrue',
                            'type' => Type::nonNull(Type::boolean()),
                            'description' => null,
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
                                        'description' => 'Foobar status Closed',
                                        'deprecationReason' => 'Its deprecated',
                                    ],
                                ],
                            ])),
                            'description' => null,
                        ],
                    ],
                    'resolve' => fn() => true,
                ],
                [
                    'name' => 'withInterface',
                    'type' => Type::nonNull(new InterfaceType([
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
                    ])),
                    'description' => null,
                    'args' => [],
                    'resolve' => fn() => true,
                ],
                [
                    'name' => 'doSomeWork',
                    'type' => Type::string(),
                    'description' => null,
                    'args' => [],
                    'resolve' => fn() => true,
                    'deprecationReason' => 'This is deprecated.',
                ],
                [
                    'name' => 'withConnectionOutput',
                    'type' => Type::nonNull(new ObjectType([
                        'name' => 'UserConnection',
                        'fields' => [
                            [
                                'name' => 'edges',
                                'type' => Type::nonNull(Type::listOf(Type::nonNull(new ObjectType([
                                    'name' => 'UserEdge',
                                    'fields' => [
                                        [
                                            'name' => 'node',
                                            'type' => Type::nonNull(new InterfaceType([
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
                                            ])),
                                            'resolve' => fn() => true,
                                        ],
                                        [
                                            'name' => 'cursor',
                                            'type' => Type::string(),
                                            'resolve' => fn() => true,
                                        ],
                                    ],
                                ])))),
                                'resolve' => fn() => true,
                            ],
                            [
                                'name' => 'pageInfo',
                                'type' => Type::nonNull(new ObjectType([
                                    'name' => 'PageInfo',
                                    'fields' => [
                                        [
                                            'name' => 'hasPreviousPage',
                                            'type' => Type::nonNull(Type::boolean()),
                                        ],
                                        [
                                            'name' => 'hasNextPage',
                                            'type' => Type::nonNull(Type::boolean()),
                                        ],
                                        [
                                            'name' => 'startCursor',
                                            'type' => Type::string(),
                                        ],
                                        [
                                            'name' => 'endCursor',
                                            'type' => Type::string(),
                                        ],
                                    ],
                                ])),
                                'resolve' => fn() => true,
                            ],
                        ],
                    ])),
                    'description' => null,
                    'args' => [
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
                                        'description' => 'Foobar status Closed',
                                        'deprecationReason' => 'Its deprecated',
                                    ],
                                ],
                            ])),
                            'description' => null,
                        ],
                        [
                            'name' => 'first',
                            'type' => Type::int(),
                            'description' => 'Connection: return the first # items',
                            'defaultValue' => 10,
                        ],
                        [
                            'name' => 'after',
                            'type' => Type::string(),
                            'description' => 'Connection: return items after cursor',
                        ],
                        [
                            'name' => 'last',
                            'type' => Type::int(),
                            'description' => 'Connection: return the last # items',
                        ],
                        [
                            'name' => 'before',
                            'type' => Type::string(),
                            'description' => 'Connection: return items before cursor',
                        ],
                    ],
                    'resolve' => fn() => true,
                ],
                [
                    'name' => 'query',
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
                                'description' => 'Foobar status Closed',
                                'deprecationReason' => 'Its deprecated',
                            ],
                        ],
                    ])),
                    'description' => null,
                    'args' => [
                        [
                            'name' => 'input',
                            'type' => Type::nonNull(new InputObjectType([
                                'name' => 'QueryInput',
                                'description' => null,
                                'fields' => [
                                    [
                                        'name' => 'queryId',
                                        'description' => 'Query id',
                                        'type' => Type::nonNull(Type::string()),
                                        'args' => [],
                                    ],
                                    [
                                        'name' => 'status',
                                        'description' => null,
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
                                                    'description' => 'Foobar status Closed',
                                                    'deprecationReason' => 'Its deprecated',
                                                ],
                                            ],
                                        ])),
                                        'args' => [],
                                    ],
                                ],
                            ])),
                            'description' => null,
                        ],
                    ],
                    'resolve' => fn() => true,
                ],
                [
                    'name' => 'withListOutput',
                    'type' => Type::listOf(Type::nonNull(new InterfaceType([
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
                    ]))),
                    'description' => null,
                    'args' => [],
                    'resolve' => fn() => true,
                ],
                [
                    'name' => 'withOverwrittenType',
                    'type' => Type::boolean(),
                    'description' => null,
                    'args' => [],
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
                                            'description' => 'Foobar status Closed',
                                            'deprecationReason' => 'Its deprecated',
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
                                        'type' => Type::nonNull(Type::string()),
                                        'description' => null,
                                    ],
                                    [
                                        'name' => 'value',
                                        'type' => Type::int(),
                                        'description' => 'The value',
                                    ],
                                ],
                                'resolve' => fn() => true,
                            ],
                            [
                                'name' => 'users',
                                'type' => new ObjectType([
                                    'name' => 'AgentConnection',
                                    'fields' => [
                                        [
                                            'name' => 'edges',
                                            'type' => Type::nonNull(Type::listOf(Type::nonNull(new ObjectType([
                                                'name' => 'AgentEdge',
                                                'fields' => [
                                                    [
                                                        'name' => 'node',
                                                        'type' => Type::nonNull(new ObjectType([
                                                            'name' => 'Agent',
                                                            'description' => null,
                                                            'fields' => [
                                                                [
                                                                    'name' => 'userId',
                                                                    'type' => Type::nonNull(Type::int()),
                                                                    'description' => null,
                                                                    'args' => [],
                                                                    'resolve' => fn() => true,
                                                                ],
                                                                [
                                                                    'name' => 'name',
                                                                    'type' => Type::nonNull(Type::string()),
                                                                    'description' => null,
                                                                    'args' => [],
                                                                    'resolve' => fn() => true,
                                                                ],
                                                                [
                                                                    'name' => 'number',
                                                                    'type' => Type::nonNull(Type::int()),
                                                                    'description' => null,
                                                                    'args' => [],
                                                                    'resolve' => fn() => true,
                                                                ],
                                                                [
                                                                    'name' => 'other',
                                                                    'type' => Type::nonNull(Type::string()),
                                                                    'description' => null,
                                                                    'args' => [],
                                                                    'resolve' => fn() => true,
                                                                ],
                                                            ],
                                                            'interfaces' => [
                                                                new InterfaceType([
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
                                                                ]),
                                                            ],
                                                        ])),
                                                        'resolve' => fn() => true,
                                                    ],
                                                    [
                                                        'name' => 'cursor',
                                                        'type' => Type::string(),
                                                        'resolve' => fn() => true,
                                                    ],
                                                ],
                                            ])))),
                                            'resolve' => fn() => true,
                                        ],
                                        [
                                            'name' => 'pageInfo',
                                            'type' => Type::nonNull(new ObjectType([
                                                'name' => 'PageInfo',
                                                'fields' => [
                                                    [
                                                        'name' => 'hasPreviousPage',
                                                        'type' => Type::nonNull(Type::boolean()),
                                                    ],
                                                    [
                                                        'name' => 'hasNextPage',
                                                        'type' => Type::nonNull(Type::boolean()),
                                                    ],
                                                    [
                                                        'name' => 'startCursor',
                                                        'type' => Type::string(),
                                                    ],
                                                    [
                                                        'name' => 'endCursor',
                                                        'type' => Type::string(),
                                                    ],
                                                ],
                                            ])),
                                            'resolve' => fn() => true,
                                        ],
                                    ],
                                ]),
                                'description' => null,
                                'args' => [
                                    [
                                        'name' => 'status',
                                        'type' => Type::string(),
                                        'description' => null,
                                    ],
                                    [
                                        'name' => 'first',
                                        'type' => Type::int(),
                                        'description' => 'Connection: return the first # items',
                                        'defaultValue' => 10,
                                    ],
                                    [
                                        'name' => 'after',
                                        'type' => Type::string(),
                                        'description' => 'Connection: return items after cursor',
                                    ],
                                    [
                                        'name' => 'last',
                                        'type' => Type::int(),
                                        'description' => 'Connection: return the last # items',
                                    ],
                                    [
                                        'name' => 'before',
                                        'type' => Type::string(),
                                        'description' => 'Connection: return items before cursor',
                                    ],
                                ],
                                'resolve' => fn() => true,
                            ],
                            [
                                'name' => 'usersList',
                                'type' => Type::listOf(Type::nonNull(new ObjectType([
                                    'name' => 'Agent',
                                    'description' => null,
                                    'fields' => [
                                        [
                                            'name' => 'userId',
                                            'type' => Type::nonNull(Type::int()),
                                            'description' => null,
                                            'args' => [],
                                            'resolve' => fn() => true,
                                        ],
                                        [
                                            'name' => 'name',
                                            'type' => Type::nonNull(Type::string()),
                                            'description' => null,
                                            'args' => [],
                                            'resolve' => fn() => true,
                                        ],
                                        [
                                            'name' => 'number',
                                            'type' => Type::nonNull(Type::int()),
                                            'description' => null,
                                            'args' => [],
                                            'resolve' => fn() => true,
                                        ],
                                        [
                                            'name' => 'other',
                                            'type' => Type::nonNull(Type::string()),
                                            'description' => null,
                                            'args' => [],
                                            'resolve' => fn() => true,
                                        ],
                                    ],
                                    'interfaces' => [
                                        new InterfaceType([
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
                                        ]),
                                    ],
                                ]))),
                                'description' => null,
                                'args' => [],
                                'resolve' => fn() => true,
                            ],
                        ],
                    ])),
                    'description' => 'Mutate a foobar',
                    'resolve' => fn() => true,
                    'args' => [
                        [
                            'name' => 'input',
                            'type' => Type::nonNull(new InputObjectType([
                                'name' => 'MutateFoobar',
                                'description' => null,
                                'fields' => [
                                    [
                                        'name' => 'id',
                                        'type' => Type::nonNull(Type::int()),
                                        'description' => null,
                                        'args' => [],
                                    ],
                                    [
                                        'name' => 'value',
                                        'type' => Type::string(),
                                        'description' => null,
                                        'args' => [],
                                    ],
                                    [
                                        'name' => 'baz',
                                        'type' => Type::nonNull(new InputObjectType([
                                            'name' => 'Baz',
                                            'description' => null,
                                            'fields' => [
                                                [
                                                    'name' => 'bazId',
                                                    'type' => Type::nonNull(Type::string()),
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
                                                                'description' => 'Foobar status Closed',
                                                                'deprecationReason' => 'Its deprecated',
                                                            ],
                                                        ],
                                                    ])),
                                                    'description' => null,
                                                    'args' => [],
                                                ],
                                            ],
                                        ])),
                                        'description' => null,
                                        'args' => [],
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
                                        'description' => null,
                                        'args' => [],
                                    ],
                                ],
                            ])),
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'name' => 'second',
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'Mutate a second foobar',
                    'resolve' => fn() => true,
                    'deprecationReason' => 'Its deprecated',
                    'args' => [
                        [
                            'name' => 'value',
                            'type' => Type::nonNull(Type::string()),
                            'description' => null,
                        ],
                    ],
                ],
            ],
        ]), $schema->getConfig()->getMutation());
    }
}
