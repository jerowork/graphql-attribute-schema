<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Type\Object;

use GraphQL\Type\Definition\CustomScalarType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestScalarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ExecutingObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\CustomScalarObjectTypeBuilder;
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
        /** @var iterable<TypeBuilder<Reference>> $nodeTypeBuilders */
        $nodeTypeBuilders = [
            new ScalarTypeBuilder(),
            new ExecutingObjectTypeBuilder(new BuiltTypesRegistry(), []),
        ];

        $type = $this->builder->build(
            new CustomScalarNode(
                TestScalarType::class,
                'TestScalar',
                null,
                DateTime::class,
            ),
            new ExecutingTypeBuilder($nodeTypeBuilders),
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
