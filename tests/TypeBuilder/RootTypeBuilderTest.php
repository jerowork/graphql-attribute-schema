<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\RootTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;

/**
 * @internal
 */
final class RootTypeBuilderTest extends TestCase
{
    private TestContainer $container;
    private RootTypeBuilder $builder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = new RootTypeBuilder(
            new TypeBuilder([]),
            new RootTypeResolver(
                $this->container = new TestContainer(),
            ),
        );
    }

    #[Test]
    public function itShouldBuildRootNode(): void
    {
        $this->container->set(TestMutation::class, new TestMutation());

        $type = $this->builder->build(
            new MutationNode(
                TestMutation::class,
                'mutation',
                'A mutation',
                [
                    new ArgNode(
                        null,
                        'int',
                        'arg',
                        'An argument',
                        false,
                        'arg',
                    ),
                ],
                null,
                'string',
                true,
                '__invoke',
            ),
            new Ast(),
        );

        self::assertEquals([
            'name' => 'mutation',
            'type' => Type::nonNull(Type::string()),
            'description' => 'A mutation',
            'args' => [
                [
                    'name' => 'arg',
                    'type' => Type::int(),
                    'description' => 'An argument',
                ],
            ],
            'resolve' => fn() => true,
        ], $type);
    }
}
