<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ConnectionReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ConnectionTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\EnumObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\InputTypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\ObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\TypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ExecutingObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\RootTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\CustomScalarNodeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\EnumNodeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\InputTypeNodeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\ScalarTypeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\EnumNodeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\ScalarTypeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
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

        $fieldResolver = new FieldResolver(
            $this->container = new TestContainer(),
            [
                new ScalarTypeOutputFieldResolver(),
                new EnumNodeOutputFieldResolver(),
            ],
        );

        /** @var iterable<ObjectTypeBuilder<Node>> $objectTypeBuilders */
        $objectTypeBuilders = [
            new EnumObjectTypeBuilder(),
            new InputTypeObjectTypeBuilder(),
            new TypeObjectTypeBuilder($fieldResolver),
        ];

        /** @var iterable<TypeBuilder<Reference>> $typeBuilders */
        $typeBuilders = [
            new ScalarTypeBuilder(),
            new ConnectionTypeBuilder($builtTypesRegistry = new BuiltTypesRegistry(), $fieldResolver),
            new ExecutingObjectTypeBuilder($builtTypesRegistry, $objectTypeBuilders),
        ];

        $this->builder = new RootTypeBuilder(
            new ExecutingTypeBuilder($typeBuilders),
            new RootTypeResolver(
                $this->container,
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
                        ScalarReference::create('int')->setNullableValue(),
                        'arg',
                        'An argument',
                        'arg',
                    ),
                ],
                ScalarReference::create('string'),
                '__invoke',
                null,
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

    #[Test]
    public function itShouldBuildRootNodeWithConnectionType(): void
    {
        $this->container->set(TestMutation::class, new TestMutation());

        $type = $this->builder->build(
            new MutationNode(
                TestMutation::class,
                'mutation',
                'A mutation',
                [
                    new ArgNode(
                        ScalarReference::create('int')->setNullableValue(),
                        'arg',
                        'An argument',
                        'arg',
                    ),
                ],
                ConnectionReference::create(TestType::class, 15),
                '__invoke',
                null,
            ),
            new Ast(
                new TypeNode(
                    TestType::class,
                    'Test',
                    null,
                    [],
                    new CursorNode(ScalarReference::create('string'), FieldNodeType::Property, null, 'property'),
                ),
            ),
        );

        self::assertEquals([
            'name' => 'mutation',
            'type' => Type::nonNull(new ObjectType([
                'name' => 'TestConnection',
                'fields' => [
                    [
                        'name' => 'edges',
                        'type' => Type::nonNull(Type::listOf(Type::nonNull(new ObjectType([
                            'name' => 'TestEdge',
                            'fields' => [
                                [
                                    'name' => 'node',
                                    'type' => Type::nonNull(new ObjectType([
                                        'name' => 'Test',
                                        'description' => null,
                                        'fields' => [],
                                    ])),
                                    'resolve' => fn() => true,
                                ],
                                [
                                    'name' => 'cursor',
                                    'type' => Type::nonNull(Type::string()),
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
            'description' => 'A mutation',
            'args' => [
                [
                    'name' => 'arg',
                    'type' => Type::int(),
                    'description' => 'An argument',
                ],
                [
                    'name' => 'first',
                    'type' => Type::int(),
                    'description' => 'Connection: return the first # items',
                    'defaultValue' => 15,
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
        ], $type);
    }
}
