<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Object;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\InputTypeNode;
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
                        null,
                        'string',
                        'field',
                        'A field description',
                        true,
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
                        FieldNodeType::Method,
                        'getField',
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
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'A field description',
                    'args' => [
                        [
                            'name' => 'arg',
                            'description' => 'An argument',
                            'type' => Type::int(),
                        ],
                    ],
                ],
            ],
        ]), $type);
    }
}
