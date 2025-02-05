<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeResolver\Field\Output;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestScalarType;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\EnumNodeOutputFieldResolver;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

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
            new ScalarNode(
                TestScalarType::class,
                'TestScalar',
                null,
                null,
            ),
        );

        self::assertTrue($this->resolver->supports(new FieldNode(
            ObjectTypeReference::create(TestEnumType::class),
            'TestEnum',
            null,
            [],
            FieldNodeType::Property,
            null,
            '',
            null,
        ), $ast));

        self::assertFalse($this->resolver->supports(new FieldNode(
            ObjectTypeReference::create(TestScalarType::class),
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
                ObjectTypeReference::create(TestEnumType::class),
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
                ObjectTypeReference::create(TestEnumType::class)->setList(),
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
