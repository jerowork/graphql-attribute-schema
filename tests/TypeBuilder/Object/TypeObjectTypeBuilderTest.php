<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Object;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
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
                        Type::createScalar('string'),
                        'field',
                        'A field description',
                        true,
                        [
                            new ArgNode(
                                Type::createScalar('int'),
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
