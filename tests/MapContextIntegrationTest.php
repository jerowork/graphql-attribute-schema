<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use GraphQL\GraphQL;
use Jerowork\GraphqlAttributeSchema\Context;
use Jerowork\GraphqlAttributeSchema\ContextException;
use Jerowork\GraphqlAttributeSchema\ParserFactory;
use Jerowork\GraphqlAttributeSchema\SchemaBuilder;
use Jerowork\GraphqlAttributeSchema\SchemaBuilderFactory;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\MapContext\CurrentUser;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\MapContext\NoopMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\MapContext\ProfileQuery;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MapContextIntegrationTest extends TestCase
{
    private SchemaBuilder $schemaBuilder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $container = new TestContainer();
        $container->set(ProfileQuery::class, new ProfileQuery());
        $container->set(NoopMutation::class, new NoopMutation());

        $this->schemaBuilder = (new SchemaBuilderFactory())->create($container);
    }

    #[Test]
    public function itShouldInjectMappedContextIntoQueryAndFieldResolvers(): void
    {
        $schema = $this->schemaBuilder->build(
            (new ParserFactory())->create()->parse(__DIR__ . '/Doubles/MapContext'),
        );

        $context = new Context();
        $context->attachObject(new CurrentUser('u1', 'Ruud'));

        $result = GraphQL::executeQuery(
            $schema,
            '{ profile { id displayName contextUserId } }',
            contextValue: $context,
        )->toArray();

        self::assertSame([
            'data' => [
                'profile' => [
                    'id' => 'u1',            // query resolver read CurrentUser via #[MapContext]
                    'displayName' => 'Ruud', // field resolver read CurrentUser via #[MapContext] from the cloned scope
                    'contextUserId' => 'u1', // field resolver received the whole Context by type
                ],
            ],
        ], $result);
    }

    #[Test]
    public function itShouldErrorWhenContextValueIsNotAContextInstance(): void
    {
        $schema = $this->schemaBuilder->build(
            (new ParserFactory())->create()->parse(__DIR__ . '/Doubles/MapContext'),
        );

        $result = GraphQL::executeQuery(
            $schema,
            '{ profile { id } }',
            contextValue: null,
        );

        self::assertNotEmpty($result->errors);
        self::assertInstanceOf(ContextException::class, $result->errors[0]->getPrevious());
    }
}
