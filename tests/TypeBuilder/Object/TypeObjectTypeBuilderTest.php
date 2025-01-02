<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Object;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\TypeObjectTypeBuilder;
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
            new FieldResolver(),
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
        $type = $this->builder->build(
            new TypeNode(
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
            ],
        ]), $type);
    }
}
