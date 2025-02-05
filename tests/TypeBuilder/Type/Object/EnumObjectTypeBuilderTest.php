<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Type\Object;

use GraphQL\Type\Definition\EnumType;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ExecutingObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\EnumObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class EnumObjectTypeBuilderTest extends TestCase
{
    private EnumObjectTypeBuilder $builder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = new EnumObjectTypeBuilder();
    }

    #[Test]
    public function itShouldSupportEnumNodeOnly(): void
    {
        self::assertTrue($this->builder->supports(new EnumNode(
            TestEnumType::class,
            'enum',
            null,
            [],
        )));

        self::assertFalse($this->builder->supports(new TypeNode(
            TestType::class,
            'type',
            null,
            [],
            null,
            false,
            [],
        )));
    }

    #[Test]
    public function itShouldBuildEnumType(): void
    {
        /** @var iterable<TypeBuilder<TypeReference>> $nodeTypeBuilders */
        $nodeTypeBuilders = [
            new ScalarTypeBuilder(),
            new ExecutingObjectTypeBuilder(new BuiltTypesRegistry(), []),
        ];

        $type = $this->builder->build(
            new EnumNode(
                TestEnumType::class,
                'enum',
                'An enum',
                [
                    new EnumValueNode('open', null, null),
                    new EnumValueNode('closed', 'Case Closed', null),
                ],
            ),
            new ExecutingTypeBuilder($nodeTypeBuilders),
            new Ast(),
        );

        self::assertEquals(new EnumType([
            'name' => 'enum',
            'description' => 'An enum',
            'values' => [
                'open' => [
                    'value' => 'open',
                    'description' => null,
                ],
                'closed' => [
                    'value' => 'closed',
                    'description' => 'Case Closed',
                ],
            ],
        ]), $type);
    }
}
