<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\SchemaBuildException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestQuery;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\RootTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;
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

        $this->schemaBuilder = new SchemaBuilder(
            new RootTypeBuilder(
                new TypeBuilder([]),
                new RootTypeResolver($this->container = new TestContainer()),
            ),
        );
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
                null,
                'string',
                true,
                '__invoke',
            ),
        ));
    }

    #[Test]
    public function itShouldBuildSchema(): void
    {
        $this->container->set(TestQuery::class, new TestQuery());
        $this->container->set(TestMutation::class, new TestMutation());

        $schema = $this->schemaBuilder->build(new Ast(
            new QueryNode(
                TestQuery::class,
                'testQuery',
                null,
                [],
                null,
                'string',
                true,
                '__invoke',
            ),
            new MutationNode(
                TestMutation::class,
                'test',
                null,
                [],
                null,
                'string',
                true,
                '__invoke',
            ),
        ));

        self::assertEquals(new ObjectType([
            'name' => 'Query',
            'fields' => [
                [
                    'name' => 'testQuery',
                    'type' => Type::nonNull(Type::string()),
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
                    'name' => 'test',
                    'type' => Type::nonNull(Type::string()),
                    'description' => null,
                    'args' => [],
                    'resolve' => fn() => true,
                ],
            ],
        ]), $schema->getConfig()->getMutation());
    }
}
