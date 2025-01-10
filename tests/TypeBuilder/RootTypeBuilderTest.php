<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder;

use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\NodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\NodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ObjectNodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarNodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\RootTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\CustomScalarNodeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\EnumNodeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\InputTypeNodeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\ScalarTypeInputFieldResolver;
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

        /** @var iterable<NodeTypeBuilder<NodeType>> $nodeTypeBuilders */
        $nodeTypeBuilders = [
            new ScalarNodeTypeBuilder(),
            new ObjectNodeTypeBuilder(new BuiltTypesRegistry(), []),
        ];

        $this->builder = new RootTypeBuilder(
            new TypeBuilder($nodeTypeBuilders),
            new RootTypeResolver(
                $this->container = new TestContainer(),
                [
                    new ScalarTypeInputFieldResolver(),
                    new CustomScalarNodeInputFieldResolver(),
                    new EnumNodeInputFieldResolver(),
                    new InputTypeNodeInputFieldResolver(),
                ],
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
                        ScalarNodeType::create('int')->setNullableValue(),
                        'arg',
                        'An argument',
                        'arg',
                    ),
                ],
                ScalarNodeType::create('string'),
                '__invoke',
                null,
            ),
            new Ast(),
        );

        self::assertEquals([
            'name' => 'mutation',
            'type' => WebonyxType::nonNull(WebonyxType::string()),
            'description' => 'A mutation',
            'args' => [
                [
                    'name' => 'arg',
                    'type' => WebonyxType::int(),
                    'description' => 'An argument',
                ],
            ],
            'resolve' => fn() => true,
        ], $type);
    }
}
