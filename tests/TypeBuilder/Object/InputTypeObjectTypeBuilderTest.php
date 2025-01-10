<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Object;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\InputTypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
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
        $type = $this->builder->build(
            new InputTypeNode(
                TestType::class,
                'type',
                'A description',
                [
                    new FieldNode(
                        Type::createScalar('string'),
                        'field',
                        'A field description',
                        [
                            new ArgNode(
                                Type::createScalar('int')->setNullableValue(),
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
            new TypeBuilder([]),
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
