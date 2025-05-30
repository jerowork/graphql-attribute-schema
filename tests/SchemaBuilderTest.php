<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use GraphQL\Type\Definition\ObjectType;
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
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithDeferredTypeLoader;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithInputObjectQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithInterfaceOutputQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithListOutputQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithOverwrittenTypeQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\WithUnionOutputQuery;
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
        $this->container->set(WithUnionOutputQuery::class, new WithUnionOutputQuery());
        $this->container->set(WithDeferredTypeLoader::class, new WithDeferredTypeLoader());

        $ast = $this->parser->parse(__DIR__ . '/Doubles/FullFeatured');
        $schema = $this->schemaBuilder->build($ast);

        self::assertSame([], $schema->validate());

        // QUERIES
        $query = $schema->getConfig()->getQuery();
        self::assertInstanceOf(ObjectType::class, $query);
        AssertSchemaConfig::assertObjectType([
            'name' => 'Query',
            'description' => null,
            'fields' => [
                [
                    'name' => 'basicName',
                    'type' => 'String!',
                    'description' => 'A description',
                    'deprecationReason' => null,
                    'args' => [
                        [
                            'name' => 'id',
                            'type' => 'Int!',
                            'description' => null,
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'name',
                            'type' => 'String',
                            'description' => null,
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'isTrue',
                            'type' => 'Boolean!',
                            'description' => null,
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'status',
                            'type' => 'FoobarStatus!',
                            'description' => null,
                            'deprecationReason' => null,
                        ],
                    ],
                ],
                [
                    'name' => 'doSomeWork',
                    'type' => 'String',
                    'description' => null,
                    'deprecationReason' => 'This is deprecated.',
                    'args' => [],
                ],
                [
                    'name' => 'query',
                    'type' => 'FoobarStatus!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [
                        [
                            'name' => 'input',
                            'type' => 'QueryInput!',
                            'description' => null,
                            'deprecationReason' => null,
                        ],
                    ],
                ],
                [
                    'name' => 'unionQuery',
                    'type' => 'Union_AgentType_FoobarType!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'withConnectionOutput',
                    'type' => 'UserConnection!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [
                        [
                            'name' => 'status',
                            'type' => 'FoobarStatus!',
                            'description' => null,
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'first',
                            'type' => 'Int',
                            'description' => 'Connection: return the first # items',
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'after',
                            'type' => 'String',
                            'description' => 'Connection: return items after cursor',
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'last',
                            'type' => 'Int',
                            'description' => 'Connection: return the last # items',
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'before',
                            'type' => 'String',
                            'description' => 'Connection: return items before cursor',
                            'deprecationReason' => null,
                        ],
                    ],
                ],
                [
                    'name' => 'withDeferredTypeLoader',
                    'type' => 'String!',
                    'description' => 'A description',
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'withInterface',
                    'type' => 'User!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'withListOutput',
                    'type' => '[User!]',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'withOverwrittenType',
                    'type' => 'Boolean',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $query, true);

        // MUTATIONS
        $mutation = $schema->getConfig()->getMutation();
        self::assertInstanceOf(ObjectType::class, $mutation);
        AssertSchemaConfig::assertObjectType([
            'name' => 'Mutation',
            'description' => null,
            'fields' => [
                [
                    'name' => 'first',
                    'type' => 'Foobar!',
                    'description' => 'Mutate a foobar',
                    'deprecationReason' => null,
                    'args' => [
                        [
                            'name' => 'input',
                            'type' => 'MutateFoobar!',
                            'description' => null,
                            'deprecationReason' => null,
                        ],
                    ],
                ],
                [
                    'name' => 'second',
                    'type' => 'String!',
                    'description' => 'Mutate a second foobar',
                    'deprecationReason' => 'Its deprecated',
                    'args' => [
                        [
                            'name' => 'value',
                            'type' => 'String!',
                            'description' => null,
                            'deprecationReason' => null,
                        ],
                    ],
                ],
            ],
        ], $mutation, true);

        // TYPES
        $types = $schema->getTypeMap();
        ksort($types);

        self::assertSame([
            'Admin',
            'Agent',
            'AgentConnection',
            'AgentEdge',
            'Baz',
            'Boolean',
            'DateTime',
            'Foobar',
            'FoobarStatus',
            'Int',
            'MutateFoobar',
            'Mutation',
            'PageInfo',
            'Query',
            'QueryInput',
            'Recipient',
            'String',
            'Union_AgentType_FoobarType',
            'User',
            'UserConnection',
            'UserEdge',
        ], array_filter(array_keys($types), fn($key) => !str_starts_with($key, '__')));

        AssertSchemaConfig::assertObjectType([
            'name' => 'Agent',
            'description' => null,
            'fields' => [
                [
                    'name' => 'recipientId',
                    'type' => 'Int!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'userId',
                    'type' => 'Int!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'adminName',
                    'type' => 'String!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'password',
                    'type' => 'String!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'isAdmin',
                    'type' => 'Boolean!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'name',
                    'type' => 'String!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'number',
                    'type' => 'Int!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'recipient',
                    'type' => 'Recipient!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'other',
                    'type' => 'String!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'parentRecipient',
                    'type' => 'Recipient!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'foobar',
                    'type' => 'Foobar!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $schema->getType('Agent'));

        AssertSchemaConfig::assertObjectType([
            'name' => 'AgentConnection',
            'description' => null,
            'fields' => [
                [
                    'name' => 'edges',
                    'type' => '[AgentEdge!]!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'pageInfo',
                    'type' => 'PageInfo!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $schema->getType('AgentConnection'));

        AssertSchemaConfig::assertObjectType([
            'name' => 'AgentEdge',
            'description' => null,
            'fields' => [
                [
                    'name' => 'node',
                    'type' => 'Agent!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'cursor',
                    'type' => 'String',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $schema->getType('AgentEdge'));

        AssertSchemaConfig::assertInputObjectType([
            'name' => 'Baz',
            'description' => null,
            'fields' => [
                [
                    'name' => 'bazId',
                    'type' => 'String!',
                    'description' => 'A baz ID',
                    'deprecationReason' => null,
                ],
                [
                    'name' => 'status',
                    'type' => 'FoobarStatus!',
                    'description' => null,
                    'deprecationReason' => null,
                ],
            ],
        ], $schema->getType('Baz'));

        AssertSchemaConfig::assertObjectType([
            'name' => 'Foobar',
            'description' => 'A foobar',
            'fields' => [
                [
                    'name' => 'foobarId',
                    'type' => 'String!',
                    'description' => 'A foobar ID',
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'status',
                    'type' => 'FoobarStatus',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'date',
                    'type' => 'DateTime',
                    'description' => 'A foobar date',
                    'deprecationReason' => null,
                    'args' => [
                        [
                            'name' => 'limiting',
                            'type' => 'String!',
                            'description' => null,
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'value',
                            'type' => 'Int',
                            'description' => 'The value',
                            'deprecationReason' => null,
                        ],
                    ],
                ],
                [
                    'name' => 'users',
                    'type' => 'AgentConnection',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [
                        [
                            'name' => 'status',
                            'type' => 'FoobarStatus',
                            'description' => null,
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'first',
                            'type' => 'Int',
                            'description' => 'Connection: return the first # items',
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'after',
                            'type' => 'String',
                            'description' => 'Connection: return items after cursor',
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'last',
                            'type' => 'Int',
                            'description' => 'Connection: return the last # items',
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'before',
                            'type' => 'String',
                            'description' => 'Connection: return items before cursor',
                            'deprecationReason' => null,
                        ],
                    ],
                ],
                [
                    'name' => 'usersList',
                    'type' => '[Agent!]',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $schema->getType('Foobar'));

        AssertSchemaConfig::assertEnumType([
            'name' => 'FoobarStatus',
            'description' => 'Foobar status',
            'values' => [
                [
                    'name' => 'open',
                    'description' => null,
                    'deprecationReason' => null,
                ],
                [
                    'name' => 'closed',
                    'description' => 'Foobar status Closed',
                    'deprecationReason' => 'Its deprecated',
                ],
            ],
        ], $schema->getType('FoobarStatus'));

        AssertSchemaConfig::assertInputObjectType([
            'name' => 'MutateFoobar',
            'description' => null,
            'fields' => [
                [
                    'name' => 'id',
                    'type' => 'Int!',
                    'description' => null,
                    'deprecationReason' => null,
                ],
                [
                    'name' => 'value',
                    'type' => 'String',
                    'description' => null,
                    'deprecationReason' => null,
                ],
                [
                    'name' => 'baz',
                    'type' => 'Baz!',
                    'description' => null,
                    'deprecationReason' => null,
                ],
                [
                    'name' => 'date',
                    'type' => 'DateTime',
                    'description' => null,
                    'deprecationReason' => null,
                ],
            ],
        ], $schema->getType('MutateFoobar'));

        AssertSchemaConfig::assertObjectType([
            'name' => 'PageInfo',
            'description' => null,
            'fields' => [
                [
                    'name' => 'hasPreviousPage',
                    'type' => 'Boolean!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'hasNextPage',
                    'type' => 'Boolean!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'startCursor',
                    'type' => 'String',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'endCursor',
                    'type' => 'String',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $schema->getType('PageInfo'));

        AssertSchemaConfig::assertInputObjectType([
            'name' => 'QueryInput',
            'description' => null,
            'fields' => [
                [
                    'name' => 'queryId',
                    'type' => 'String!',
                    'description' => 'Query id',
                    'deprecationReason' => null,
                ],
                [
                    'name' => 'status',
                    'type' => 'FoobarStatus!',
                    'description' => null,
                    'deprecationReason' => null,
                ],
            ],
        ], $schema->getType('QueryInput'));

        AssertSchemaConfig::assertInterfaceType([
            'name' => 'Recipient',
            'description' => null,
            'fields' => [
                [
                    'name' => 'recipientId',
                    'type' => 'Int!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $schema->getType('Recipient'));

        AssertSchemaConfig::assertUnionType([
            'name' => 'Union_AgentType_FoobarType',
            'description' => null,
        ], $schema->getType('Union_AgentType_FoobarType'));

        AssertSchemaConfig::assertInterfaceType([
            'name' => 'User',
            'description' => null,
            'fields' => [
                [
                    'name' => 'userId',
                    'type' => 'Int!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $schema->getType('User'));

        AssertSchemaConfig::assertObjectType([
            'name' => 'UserConnection',
            'description' => null,
            'fields' => [
                [
                    'name' => 'edges',
                    'type' => '[UserEdge!]!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'pageInfo',
                    'type' => 'PageInfo!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $schema->getType('UserConnection'));

        AssertSchemaConfig::assertObjectType([
            'name' => 'UserEdge',
            'description' => null,
            'fields' => [
                [
                    'name' => 'node',
                    'type' => 'User!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'cursor',
                    'type' => 'String',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $schema->getType('UserEdge'));
    }
}
