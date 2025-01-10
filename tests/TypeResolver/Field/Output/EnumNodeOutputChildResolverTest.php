<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeResolver\Field\Output;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestScalarType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\EnumNodeOutputFieldResolver;
use Override;

/**
 * @internal
 */
final class EnumNodeOutputChildResolverTest extends TestCase
{
    private EnumNodeOutputFieldResolver $resolver;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new EnumNodeOutputFieldResolver();
    }

    #[Test]
    public function itShouldSupportEnumNodeOnly(): void
    {
        $ast = new Ast(
            new EnumNode(
                TestEnumType::class,
                'TestEnum',
                null,
                [],
            ),
            new CustomScalarNode(
                TestScalarType::class,
                'TestScalar',
                null,
                null,
            ),
        );

        self::assertTrue($this->resolver->supports(new FieldNode(
            ObjectNodeType::create(TestEnumType::class),
            'TestEnum',
            null,
            [],
            FieldNodeType::Property,
            null,
            '',
            null,
        ), $ast));

        self::assertFalse($this->resolver->supports(new FieldNode(
            ObjectNodeType::create(TestScalarType::class),
            'TestScalar',
            null,
            [],
            FieldNodeType::Property,
            null,
            '',
            null,
        ), $ast));
    }

    #[Test]
    public function itShouldResolveEnum(): void
    {
        $ast = new Ast(
            new EnumNode(
                TestEnumType::class,
                'TestEnum',
                null,
                [],
            ),
        );

        $output = $this->resolver->resolve(
            new FieldNode(
                ObjectNodeType::create(TestEnumType::class),
                'TestEnum',
                null,
                [],
                FieldNodeType::Property,
                null,
                '',
                null,
            ),
            fn() => TestEnumType::B,
            $ast,
        );

        self::assertSame('b', $output);
    }

    #[Test]
    public function itShouldListOfEnums(): void
    {
        $ast = new Ast(
            new EnumNode(
                TestEnumType::class,
                'TestEnum',
                null,
                [],
            ),
        );

        $output = $this->resolver->resolve(
            new FieldNode(
                ObjectNodeType::create(TestEnumType::class)->setList(),
                'TestEnum',
                null,
                [],
                FieldNodeType::Property,
                null,
                '',
                null,
            ),
            fn() => [TestEnumType::B, TestEnumType::C],
            $ast,
        );

        self::assertSame(['b', 'c'], $output);
    }
}
