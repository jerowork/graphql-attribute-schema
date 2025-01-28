<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ExecutingObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\CustomScalarObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\EnumObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\InputTypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\ObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\TypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\EnumNodeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\ScalarTypeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ConnectionTypeBuilder;
use Override;

/**
 * @internal
 */
final class ConnectionTypeBuilderTest extends TestCase
{
    private ConnectionTypeBuilder $builder;
    private ExecutingTypeBuilder $typeBuilder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = new ConnectionTypeBuilder(
            $builtTypesRegistry = new BuiltTypesRegistry(),
            $fieldResolver = new FieldResolver(
                new TestContainer(),
                [
                    new ScalarTypeOutputFieldResolver(),
                    new EnumNodeOutputFieldResolver(),
                ],
            ),
        );

        /** @var list<ObjectTypeBuilder<Node>> $objectTypeBuilders */
        $objectTypeBuilders = [
            new CustomScalarObjectTypeBuilder(),
            new EnumObjectTypeBuilder(),
            new InputTypeObjectTypeBuilder(),
            new TypeObjectTypeBuilder($builtTypesRegistry, $fieldResolver),
        ];

        /** @var list<TypeBuilder<TypeReference>> $typeBuilders */
        $typeBuilders = [
            new ScalarTypeBuilder(),
            new ConnectionTypeBuilder($builtTypesRegistry, $fieldResolver),
            new ExecutingObjectTypeBuilder(
                $builtTypesRegistry,
                $objectTypeBuilders,
            ),
        ];

        $this->typeBuilder = new ExecutingTypeBuilder($typeBuilders);
    }

    #[Test]
    public function itShouldGuardNodeAvailable(): void
    {
        self::expectException(BuildException::class);
        self::expectExceptionMessage('No node found for connection');

        $this->builder->build(
            ConnectionTypeReference::create(TestType::class, 15),
            $this->typeBuilder,
            new Ast(),
        );
    }

    #[Test]
    public function itShouldGuardTypeNode(): void
    {
        self::expectException(BuildException::class);
        self::expectExceptionMessage('Invalid edge node for connection');

        $this->builder->build(
            ConnectionTypeReference::create(TestType::class, 15),
            $this->typeBuilder,
            new Ast(
                new ScalarNode(
                    TestType::class,
                    'Test',
                    null,
                    null,
                ),
            ),
        );
    }

    #[Test]
    public function itShouldBuildConnection(): void
    {
        $connection = $this->builder->build(
            ConnectionTypeReference::create(TestType::class, 15),
            $this->typeBuilder,
            new Ast(
                new TypeNode(
                    TestType::class,
                    'Test',
                    null,
                    [],
                    new CursorNode(
                        ScalarTypeReference::create('string'),
                        FieldNodeType::Property,
                        null,
                        'property',
                    ),
                    false,
                    [],
                ),
            ),
        );

        self::assertEquals(new ObjectType([
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
        ]), $connection);
    }
}
