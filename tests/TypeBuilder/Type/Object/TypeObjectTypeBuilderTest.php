<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Type\Object;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ConnectionReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ConnectionTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\CustomScalarObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\EnumObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\InputTypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\ObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ExecutingObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\EnumNodeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\ScalarTypeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\TypeObjectTypeBuilder;
use Override;

/**
 * @internal
 */
final class TypeObjectTypeBuilderTest extends TestCase
{
    private TypeObjectTypeBuilder $builder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = new TypeObjectTypeBuilder(
            new FieldResolver(
                new TestContainer(),
                [
                    new ScalarTypeOutputFieldResolver(),
                    new EnumNodeOutputFieldResolver(),
                ],
            ),
        );
    }

    #[Test]
    public function itShouldSupportTypeNodeOnly(): void
    {
        self::assertTrue($this->builder->supports(new TypeNode(
            TestType::class,
            'type',
            null,
            [],
            null,
        )));

        self::assertFalse($this->builder->supports(new EnumNode(
            TestEnumType::class,
            'enum',
            null,
            [],
        )));
    }

    #[Test]
    public function itShouldBuildType(): void
    {
        /** @var iterable<ObjectTypeBuilder<Node>> $objectTypeBuilders */
        $objectTypeBuilders = [
            new CustomScalarObjectTypeBuilder(),
            new EnumObjectTypeBuilder(),
            new InputTypeObjectTypeBuilder(),
            new TypeObjectTypeBuilder($fieldResolver = new FieldResolver(
                new TestContainer(),
                [
                    new ScalarTypeOutputFieldResolver(),
                    new EnumNodeOutputFieldResolver(),
                ],
            )),
        ];

        /** @var iterable<TypeBuilder<Reference>> $typeBuilders */
        $typeBuilders = [
            new ScalarTypeBuilder(),
            new ConnectionTypeBuilder($builtTypesRegistry = new BuiltTypesRegistry(), $fieldResolver),
            new ExecutingObjectTypeBuilder($builtTypesRegistry, $objectTypeBuilders),
        ];

        $type = $this->builder->build(
            new TypeNode(
                TestType::class,
                'type',
                'A description',
                [
                    new FieldNode(
                        ScalarReference::create('string'),
                        'field',
                        'A field description',
                        [
                            new ArgNode(
                                ScalarReference::create('int')->setNullableValue(),
                                'arg',
                                'An argument',
                                'arg',
                            ),
                        ],
                        FieldNodeType::Method,
                        'getField',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ConnectionReference::create(TestType::class, 15),
                        'name',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'name',
                        null,
                    ),
                ],
                null,
            ),
            new ExecutingTypeBuilder($typeBuilders),
            new Ast(
                new TypeNode(
                    TestType::class,
                    'Test',
                    null,
                    [],
                    new CursorNode(
                        ScalarReference::create('string'),
                        FieldNodeType::Property,
                        null,
                        'property',
                    ),
                ),
            ),
        );

        self::assertEquals(new ObjectType([
            'name' => 'type',
            'description' => 'A description',
            'fields' => [
                [
                    'name' => 'field',
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'A field description',
                    'args' => [
                        [
                            'name' => 'arg',
                            'description' => 'An argument',
                            'type' => Type::int(),
                        ],
                    ],
                    'resolve' => fn() => true,
                ],
                [
                    'name' => 'name',
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
                    'description' => null,
                    'args' => [
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
                ],
            ],
        ]), $type);
    }
}
