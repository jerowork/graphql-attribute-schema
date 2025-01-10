<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Object;

use GraphQL\Type\Definition\CustomScalarType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestScalarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\CustomScalarObjectTypeBuilder;
use PHPUnit\Framework\Attributes\Test;
use Override;
use DateTime;

/**
 * @internal
 */
final class CustomScalarObjectTypeBuilderTest extends TestCase
{
    private CustomScalarObjectTypeBuilder $builder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = new CustomScalarObjectTypeBuilder();
    }

    #[Test]
    public function itShouldSupportScalarNodeOnly(): void
    {
        self::assertTrue($this->builder->supports(new CustomScalarNode(
            TestScalarType::class,
            'enum',
            null,
            DateTime::class,
        )));

        self::assertFalse($this->builder->supports(new TypeNode(
            TestType::class,
            'type',
            null,
            [],
        )));
    }

    #[Test]
    public function itShouldBuildCustomScalarType(): void
    {
        $type = $this->builder->build(
            new CustomScalarNode(
                TestScalarType::class,
                'TestScalar',
                null,
                DateTime::class,
            ),
            new TypeBuilder(new BuiltTypesRegistry(), []),
            new Ast(),
        );

        self::assertEquals(new CustomScalarType([
            'name' => 'TestScalar',
            'serialize' => fn() => true,
            'parseValue' => fn() => true,
            'parseLiteral' => fn() => true,
            'description' => null,
        ]), $type);
    }
}
