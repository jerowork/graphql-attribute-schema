<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use DateTime;
use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\InterfaceTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\UnionTypeReference;
use Jerowork\GraphqlAttributeSchema\Parser;
use Jerowork\GraphqlAttributeSchema\ParserFactory;
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
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\AbstractAdminType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\AgentType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarStatusType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input\Baz;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input\MutateFoobarInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input\QueryInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\RecipientType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Scalar\TestScalarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\UserType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader\TestTypeLoader;
use Jerowork\GraphqlAttributeSchema\Type\DateTimeType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

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

        $this->parser = (new ParserFactory())->create();
    }

    #[Test]
    public function itShouldParseDirectory(): void
    {
        $ast = $this->parser->parse(__DIR__ . '/Doubles/FullFeatured');

        $nodes = $ast->getNodesByNodeType(MutationNode::class);
        usort($nodes, fn(MutationNode $a, MutationNode $b) => $a->name <=> $b->name);
        self::assertEquals([
            new MutationNode(
                BasicMutation::class,
                'first',
                'Mutate a foobar',
                [
                    new ArgNode(
                        ObjectTypeReference::create(MutateFoobarInputType::class),
                        'input',
                        null,
                        'input',
                    ),
                ],
                ObjectTypeReference::create(FoobarType::class),
                '__invoke',
                null,
                null,
            ),
            new MutationNode(
                BasicMutation::class,
                'second',
                'Mutate a second foobar',
                [
                    new ArgNode(
                        ScalarTypeReference::create('string'),
                        'value',
                        null,
                        'value',
                    ),
                ],
                ScalarTypeReference::create('string'),
                'second',
                'Its deprecated',
                null,
            ),
        ], $nodes);

        $nodes = $ast->getNodesByNodeType(QueryNode::class);
        usort($nodes, fn(QueryNode $a, QueryNode $b) => $a->name <=> $b->name);
        self::assertEquals([
            new QueryNode(
                BasicQuery::class,
                'basicName',
                'A description',
                [
                    new ArgNode(
                        ScalarTypeReference::create('int'),
                        'id',
                        null,
                        'id',
                    ),
                    new ArgNode(
                        ScalarTypeReference::create('string')->setNullableValue(),
                        'name',
                        null,
                        'name',
                    ),
                    new ArgNode(
                        ScalarTypeReference::create('bool'),
                        'isTrue',
                        null,
                        'isTrue',
                    ),
                    new ArgNode(
                        ObjectTypeReference::create(FoobarStatusType::class),
                        'status',
                        null,
                        'status',
                    ),
                ],
                ScalarTypeReference::create('string'),
                '__invoke',
                null,
                null,
            ),
            new QueryNode(
                DeprecatedQuery::class,
                'doSomeWork',
                null,
                [],
                ScalarTypeReference::create('string')->setNullableValue(),
                'doSomeWork',
                'This is deprecated.',
                null,
            ),
            new QueryNode(
                WithInputObjectQuery::class,
                'query',
                null,
                [
                    new ArgNode(
                        ObjectTypeReference::create(QueryInputType::class),
                        'input',
                        null,
                        'input',
                    ),
                ],
                ObjectTypeReference::create(FoobarStatusType::class),
                'query',
                null,
                null,
            ),
            new QueryNode(
                WithUnionOutputQuery::class,
                'unionQuery',
                null,
                [],
                UnionTypeReference::create('Union_AgentType_FoobarType', [AgentType::class, FoobarType::class]),
                'getUnionQuery',
                null,
                null,
            ),
            new QueryNode(
                WithConnectionOutputQuery::class,
                'withConnectionOutput',
                null,
                [
                    new EdgeArgsNode('edgeArgs'),
                    new ArgNode(
                        ObjectTypeReference::create(FoobarStatusType::class),
                        'status',
                        null,
                        'status',
                    ),
                ],
                ConnectionTypeReference::create(UserType::class),
                'withConnectionOutput',
                null,
                null,
            ),
            new QueryNode(
                WithDeferredTypeLoader::class,
                'withDeferredTypeLoader',
                'A description',
                [],
                ScalarTypeReference::create('string'),
                '__invoke',
                null,
                TestTypeLoader::class,
            ),
            new QueryNode(
                WithInterfaceOutputQuery::class,
                'withInterface',
                null,
                [],
                ObjectTypeReference::create(UserType::class),
                'withInterface',
                null,
                null,
            ),
            new QueryNode(
                WithListOutputQuery::class,
                'withListOutput',
                null,
                [],
                ObjectTypeReference::create(UserType::class)->setList()->setNullableList(),
                'withListOutput',
                null,
                null,
            ),
            new QueryNode(
                WithOverwrittenTypeQuery::class,
                'withOverwrittenType',
                null,
                [],
                ScalarTypeReference::create('bool')->setNullableValue(),
                'withOverwrittenType',
                null,
                null,
            ),
        ], $nodes);

        $nodes = $ast->getNodesByNodeType(InputTypeNode::class);
        usort($nodes, fn(InputTypeNode $a, InputTypeNode $b) => $a->name <=> $b->name);
        self::assertEquals([
            new InputTypeNode(
                Baz::class,
                'Baz',
                null,
                [
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'bazId',
                        'A baz ID',
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(FoobarStatusType::class),
                        'status',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'status',
                        null,
                        null,
                    ),
                ],
            ),
            new InputTypeNode(
                MutateFoobarInputType::class,
                'MutateFoobar',
                null,
                [
                    new FieldNode(
                        ScalarTypeReference::create('int'),
                        'id',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ScalarTypeReference::create('string')->setNullableValue(),
                        'value',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'value',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(Baz::class),
                        'baz',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'baz',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(DateTimeImmutable::class)->setNullableValue(),
                        'date',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'date',
                        null,
                        null,
                    ),
                ],
            ),
            new InputTypeNode(
                QueryInputType::class,
                'QueryInput',
                null,
                [
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'queryId',
                        'Query id',
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(FoobarStatusType::class),
                        'status',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'status',
                        null,
                        null,
                    ),
                ],
            ),
        ], $nodes);

        $nodes = $ast->getNodesByNodeType(InterfaceTypeNode::class);
        usort($nodes, fn(InterfaceTypeNode $a, InterfaceTypeNode $b) => $a->name <=> $b->name);
        self::assertEquals([
            new InterfaceTypeNode(
                AbstractAdminType::class,
                'Admin',
                null,
                [
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'adminName',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'adminName',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'password',
                        null,
                        [],
                        FieldNodeType::Method,
                        'getPassword',
                        null,
                        null,
                        null,
                    ),
                    new FieldNode(
                        ScalarTypeReference::create('bool'),
                        'isAdmin',
                        null,
                        [],
                        FieldNodeType::Method,
                        'isAdmin',
                        null,
                        null,
                        null,
                    ),
                    new FieldNode(
                        ScalarTypeReference::create('int'),
                        'recipientId',
                        null,
                        [],
                        FieldNodeType::Method,
                        'getRecipientId',
                        null,
                        null,
                        null,
                    ),
                ],
                null,
                [RecipientType::class],
            ),
            new InterfaceTypeNode(
                RecipientType::class,
                'Recipient',
                null,
                [
                    new FieldNode(
                        ScalarTypeReference::create('int'),
                        'recipientId',
                        null,
                        [],
                        FieldNodeType::Method,
                        'getRecipientId',
                        null,
                        null,
                        null,
                    ),
                ],
                null,
                [],
            ),
            new InterfaceTypeNode(
                UserType::class,
                'User',
                null,
                [
                    new FieldNode(
                        ScalarTypeReference::create('int'),
                        'userId',
                        null,
                        [],
                        FieldNodeType::Method,
                        'getId',
                        null,
                        null,
                        null,
                    ),
                ],
                null,
                [],
            ),
        ], $nodes);

        $nodes = $ast->getNodesByNodeType(TypeNode::class);
        usort($nodes, fn(TypeNode $a, TypeNode $b) => $a->name <=> $b->name);
        self::assertEquals([
            new TypeNode(
                AgentType::class,
                'Agent',
                null,
                [
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'name',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'name',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ScalarTypeReference::create('int'),
                        'number',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'number',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(RecipientType::class),
                        'recipient',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'recipient',
                        null,
                        TestTypeLoader::class,
                    ),
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'adminName',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'adminName',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ScalarTypeReference::create('int'),
                        'recipientId',
                        null,
                        [],
                        FieldNodeType::Method,
                        'getRecipientId',
                        null,
                        null,
                        null,
                    ),
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'other',
                        null,
                        [],
                        FieldNodeType::Method,
                        'getOther',
                        null,
                        null,
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(RecipientType::class),
                        'parentRecipient',
                        null,
                        [],
                        FieldNodeType::Method,
                        'getParentRecipient',
                        null,
                        null,
                        TestTypeLoader::class,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(FoobarType::class),
                        'foobar',
                        null,
                        [],
                        FieldNodeType::Method,
                        'getFoobar',
                        null,
                        null,
                        null,
                    ),
                    new FieldNode(
                        ScalarTypeReference::create('bool'),
                        'isAdmin',
                        null,
                        [],
                        FieldNodeType::Method,
                        'isAdmin',
                        null,
                        null,
                        null,
                    ),
                ],
                null,
                [
                    RecipientType::class,
                    UserType::class,
                    AbstractAdminType::class,
                ],
            ),
            new TypeNode(
                FoobarType::class,
                'Foobar',
                'A foobar',
                [
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'foobarId',
                        'A foobar ID',
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(FoobarStatusType::class)->setNullableValue(),
                        'status',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'status',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(DateTimeImmutable::class)->setNullableValue(),
                        'date',
                        'A foobar date',
                        [
                            new AutowireNode(DateTimeImmutable::class, 'service'),
                            new ArgNode(
                                ScalarTypeReference::create('string'),
                                'limiting',
                                null,
                                'limit',
                            ),
                            new ArgNode(
                                ScalarTypeReference::create('int')->setNullableValue(),
                                'value',
                                'The value',
                                'value',
                            ),
                        ],
                        FieldNodeType::Method,
                        'getDate',
                        null,
                        null,
                        null,
                    ),
                    new FieldNode(
                        ConnectionTypeReference::create(AgentType::class)->setNullableValue(),
                        'users',
                        null,
                        [
                            new EdgeArgsNode('edgeArgs'),
                            new ArgNode(
                                ObjectTypeReference::create(FoobarStatusType::class)->setNullableValue(),
                                'status',
                                null,
                                'status',
                            ),
                        ],
                        FieldNodeType::Method,
                        'getUsers',
                        null,
                        null,
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(AgentType::class)->setList()->setNullableList(),
                        'usersList',
                        null,
                        [],
                        FieldNodeType::Method,
                        'getUsersList',
                        null,
                        null,
                        null,
                    ),
                ],
                null,
                [],
            ),
        ], $nodes);

        self::assertEquals([
            new EnumNode(
                FoobarStatusType::class,
                'FoobarStatus',
                'Foobar status',
                [
                    new EnumValueNode('open', null, null),
                    new EnumValueNode('closed', 'Foobar status Closed', 'Its deprecated'),
                ],
            ),
        ], $ast->getNodesByNodeType(EnumNode::class));

        $nodes = $ast->getNodesByNodeType(ScalarNode::class);
        usort($nodes, fn(ScalarNode $a, ScalarNode $b) => $a->name <=> $b->name);
        self::assertEquals([
            new ScalarNode(
                DateTimeType::class,
                'DateTime',
                'Date and time (ISO-8601)',
                DateTimeImmutable::class,
            ),
            new ScalarNode(
                TestScalarType::class,
                'TestScalar',
                null,
                DateTime::class,
            ),
        ], $nodes);
    }
}
