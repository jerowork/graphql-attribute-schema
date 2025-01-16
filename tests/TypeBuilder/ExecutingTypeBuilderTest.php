<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ConnectionTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\EnumObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\InputTypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\ObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\TypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ExecutingObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\EnumNodeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\ScalarTypeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Override;

/**
 * @internal
 */
final class ExecutingTypeBuilderTest extends TestCase
{
    private ExecutingTypeBuilder $builder;

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

        /** @var iterable<TypeBuilder<Reference>> $typeBuilders */
        $typeBuilders = [
            new ScalarTypeBuilder(),
            new ConnectionTypeBuilder($builtTypesRegistry = new BuiltTypesRegistry(), new FieldResolver(
                new TestContainer(),
                [
                    new ScalarTypeOutputFieldResolver(),
                    new EnumNodeOutputFieldResolver(),
                ],
            )),
            new ExecutingObjectTypeBuilder($builtTypesRegistry, $objectTypeBuilders),
        ];

        $this->builder = new ExecutingTypeBuilder($typeBuilders);
    }

    #[Test]
    public function itShouldBuildNullableScalarType(): void
    {
        $type = $this->builder->build(ScalarReference::create('string')->setNullableValue(), new Ast());

        self::assertEquals(WebonyxType::string(), $type);
    }

    #[Test]
    public function it_should_build_non_nullable_scalar_type(): void
    {
        $type = $this->builder->build(ScalarReference::create('float'), new Ast());

        self::assertEquals(WebonyxType::nonNull(WebonyxType::float()), $type);
    }

    #[Test]
    public function itShouldGuardMissingNode(): void
    {
        self::expectException(BuildException::class);
        self::expectExceptionMessage('No node found for class');

        $this->builder->build(
            ObjectReference::create(TestEnumType::class),
            new Ast(),
        );
    }

    #[Test]
    public function itShouldBuildNullableObjectType(): void
    {
        $type = $this->builder->build(
            ObjectReference::create(TestEnumType::class)->setNullableValue(),
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
            ObjectReference::create(TestEnumType::class),
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
