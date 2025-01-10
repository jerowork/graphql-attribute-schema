<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\NodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\NodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\EnumObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\InputTypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\ObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\TypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ObjectNodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarNodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\EnumNodeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\ScalarTypeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Override;

/**
 * @internal
 */
final class TypeBuilderTest extends TestCase
{
    private TypeBuilder $builder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        /** @var iterable<ObjectTypeBuilder<Node>> $objectTypeBuilders */
        $objectTypeBuilders = [
            new EnumObjectTypeBuilder(),
            new InputTypeObjectTypeBuilder(),
            new TypeObjectTypeBuilder(new FieldResolver(
                new TestContainer(),
                [
                    new ScalarTypeOutputFieldResolver(),
                    new EnumNodeOutputFieldResolver(),
                ],
            )),
        ];

        /** @var iterable<NodeTypeBuilder<NodeType>> $nodeTypeBuilders */
        $nodeTypeBuilders = [
            new ScalarNodeTypeBuilder(),
            new ObjectNodeTypeBuilder(new BuiltTypesRegistry(), $objectTypeBuilders),
        ];

        $this->builder = new TypeBuilder($nodeTypeBuilders);
    }

    #[Test]
    public function itShouldBuildNullableScalarType(): void
    {
        $type = $this->builder->build(ScalarNodeType::create('string')->setNullableValue(), new Ast());

        self::assertEquals(WebonyxType::string(), $type);
    }

    #[Test]
    public function it_should_build_non_nullable_scalar_type(): void
    {
        $type = $this->builder->build(ScalarNodeType::create('float'), new Ast());

        self::assertEquals(WebonyxType::nonNull(WebonyxType::float()), $type);
    }

    #[Test]
    public function itShouldGuardMissingNode(): void
    {
        self::expectException(BuildException::class);
        self::expectExceptionMessage('No node found for class');

        $this->builder->build(
            ObjectNodeType::create(TestEnumType::class),
            new Ast(),
        );
    }

    #[Test]
    public function itShouldBuildNullableObjectType(): void
    {
        $type = $this->builder->build(
            ObjectNodeType::create(TestEnumType::class)->setNullableValue(),
            new Ast(
                new EnumNode(
                    TestEnumType::class,
                    'TestEnum',
                    'A description',
                    [
                        new EnumValueNode('open', null, null),
                        new EnumValueNode('closed', null, null),
                    ],
                ),
            ),
        );

        self::assertEquals(new EnumType([
            'name' => 'TestEnum',
            'description' => 'A description',
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
        ]), $type);
    }

    #[Test]
    public function itShouldBuildNonNullableObjectType(): void
    {
        $type = $this->builder->build(
            ObjectNodeType::create(TestEnumType::class),
            new Ast(
                new EnumNode(
                    TestEnumType::class,
                    'TestEnum',
                    'A description',
                    [
                        new EnumValueNode('open', null, null),
                        new EnumValueNode('closed', null, null),
                    ],
                ),
            ),
        );

        self::assertEquals(WebonyxType::nonNull(new EnumType([
            'name' => 'TestEnum',
            'description' => 'A description',
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
        ])), $type);
    }
}
