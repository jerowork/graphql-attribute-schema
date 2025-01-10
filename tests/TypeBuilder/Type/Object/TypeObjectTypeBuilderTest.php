<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\NodeType\Object;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\NodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\NodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ObjectNodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarNodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
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
        /** @var iterable<NodeTypeBuilder<NodeType>> $nodeTypeBuilders */
        $nodeTypeBuilders = [
            new ScalarNodeTypeBuilder(),
            new ObjectNodeTypeBuilder(new BuiltTypesRegistry(), []),
        ];

        $type = $this->builder->build(
            new TypeNode(
                TestType::class,
                'type',
                'A description',
                [
                    new FieldNode(
                        ScalarNodeType::create('string'),
                        'field',
                        'A field description',
                        [
                            new ArgNode(
                                ScalarNodeType::create('int')->setNullableValue(),
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
                ],
            ),
            new TypeBuilder($nodeTypeBuilders),
            new Ast(),
        );

        self::assertEquals(new ObjectType([
            'name' => 'type',
            'description' => 'A description',
            'fields' => [
                [
                    'name' => 'field',
                    'type' => WebonyxType::nonNull(WebonyxType::string()),
                    'description' => 'A field description',
                    'args' => [
                        [
                            'name' => 'arg',
                            'description' => 'An argument',
                            'type' => WebonyxType::int(),
                        ],
                    ],
                    'resolve' => fn() => true,
                ],
            ],
        ]), $type);
    }
}
