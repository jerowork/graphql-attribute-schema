<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Type\Object;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\InputTypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ExecutingObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Override;

/**
 * @internal
 */
final class InputTypeObjectTypeBuilderTest extends TestCase
{
    private InputTypeObjectTypeBuilder $builder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = new InputTypeObjectTypeBuilder();
    }

    #[Test]
    public function itShouldSupportInputTypeNodeOnly(): void
    {
        self::assertTrue($this->builder->supports(new InputTypeNode(
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
        /** @var iterable<TypeBuilder<TypeReference>> $nodeTypeBuilders */
        $nodeTypeBuilders = [
            new ScalarTypeBuilder(),
            new ExecutingObjectTypeBuilder(new BuiltTypesRegistry(), []),
        ];

        $type = $this->builder->build(
            new InputTypeNode(
                TestType::class,
                'type',
                'A description',
                [
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'field',
                        'A field description',
                        [
                            new ArgNode(
                                ScalarTypeReference::create('int')->setNullableValue(),
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
            new ExecutingTypeBuilder($nodeTypeBuilders),
            new Ast(),
        );

        self::assertEquals(new InputObjectType([
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
                ],
            ],
        ]), $type);
    }
}
