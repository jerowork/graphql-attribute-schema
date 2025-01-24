<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ScalarClassNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\EnumClassNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\InputTypeClassNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeClassNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\MutationMethodNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\QueryMethodNodeParser;
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
            [
                new EnumClassNodeParser(),
                new InputTypeClassNodeParser($classFieldNodesParser = new ClassFieldNodesParser(
                    $typeReferenceDecider = new TypeReferenceDecider(),
                    $methodArgsNodeParser = new MethodArgumentNodesParser(
                        new AutowireNodeParser(),
                        new EdgeArgsNodeParser(),
                        new ArgNodeParser($typeReferenceDecider),
                    ),
                )),
                new TypeClassNodeParser($classFieldNodesParser, new CursorNodeParser($typeReferenceDecider)),
                new ScalarClassNodeParser(),
                new MutationMethodNodeParser($typeReferenceDecider, $methodArgsNodeParser),
                new QueryMethodNodeParser($typeReferenceDecider, $methodArgsNodeParser),
            ],
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
                    'type' => WebonyxType::nonNull(WebonyxType::listOf(WebonyxType::nonNull(WebonyxType::string()))),
                    'description' => 'Get a Foobar',
                    'args' => [
                        [
                            'name' => 'id',
                            'type' => WebonyxType::int(),
                            'description' => null,
                        ],
                        [
                            'name' => 'date',
                            'type' => WebonyxType::nonNull(new CustomScalarType([
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
                            'type' => WebonyxType::nonNull(WebonyxType::listOf(WebonyxType::nonNull(WebonyxType::boolean()))),
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
                    'type' => WebonyxType::nonNull(new ObjectType([
                        'name' => 'Foobar',
                        'description' => 'A foobar',
                        'fields' => [
                            [
                                'name' => 'foobarId',
                                'type' => WebonyxType::nonNull(WebonyxType::string()),
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
                                        'type' => WebonyxType::nonNull(WebonyxType::string()),
                                    ],
                                    [
                                        'name' => 'value',
                                        'description' => 'The value',
                                        'type' => WebonyxType::int(),
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
                            'type' => WebonyxType::nonNull(new InputObjectType([
                                'name' => 'MutateFoobar',
                                'description' => null,
                                'fields' => [
                                    [
                                        'name' => 'id',
                                        'type'=> WebonyxType::nonNull(WebonyxType::int()),
                                        'args' => [],
                                        'description' => null,
                                    ],
                                    [
                                        'name' => 'value',
                                        'type' => WebonyxType::string(),
                                        'args' => [],
                                        'description' => null,
                                    ],
                                    [
                                        'name' => 'baz',
                                        'type' => WebonyxType::nonNull(new InputObjectType([
                                            'name' => 'Baz',
                                            'description' => null,
                                            'fields' => [
                                                [
                                                    'name' => 'bazId',
                                                    'type'=> WebonyxType::nonNull(WebonyxType::string()),
                                                    'description' => 'A baz ID',
                                                    'args' => [],
                                                ],
                                                [
                                                    'name' => 'status',
                                                    'type' => WebonyxType::nonNull(new EnumType([
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
                    'type' => WebonyxType::nonNull(WebonyxType::string()),
                    'description' => 'Mutate a second foobar',
                    'args' => [
                        [
                            'name' => 'value',
                            'type' => WebonyxType::nonNull(WebonyxType::string()),
                            'description' => null,
                        ],
                    ],
                    'resolve' => fn() => true,
                ],
            ],
        ]), $schema->getConfig()->getMutation());
    }
}
