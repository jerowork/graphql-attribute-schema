<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Object;

use GraphQL\Type\Definition\EnumType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\EnumObjectTypeBuilder;
use Override;

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
        )));
    }

    #[Test]
    public function itShouldBuildEnumType(): void
    {
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
            new TypeBuilder(new BuiltTypesRegistry(), []),
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
