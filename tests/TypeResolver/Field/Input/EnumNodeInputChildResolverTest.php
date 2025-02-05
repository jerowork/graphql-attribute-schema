<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestScalarType;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\EnumNodeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class EnumNodeInputChildResolverTest extends TestCase
{
    private EnumNodeInputFieldResolver $resolver;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new EnumNodeInputFieldResolver();
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

        $enum = $this->resolver->resolve(
            new FieldNode(
                ObjectTypeReference::create(TestEnumType::class),
                'enum',
                null,
                [],
                FieldNodeType::Property,
                null,
                'TestEnum',
                null,
            ),
            [
                'enum' => 'a',
            ],
            $ast,
            new RootTypeResolver(new TestContainer(), []),
        );

        self::assertSame(TestEnumType::A, $enum);
    }

    #[Test]
    public function itShouldResolveListOfEnums(): void
    {
        $ast = new Ast(
            new EnumNode(
                TestEnumType::class,
                'TestEnum',
                null,
                [],
            ),
        );

        $enum = $this->resolver->resolve(
            new FieldNode(
                ObjectTypeReference::create(TestEnumType::class)->setList(),
                'enum',
                null,
                [],
                FieldNodeType::Property,
                null,
                'TestEnum',
                null,
            ),
            [
                'enum' => ['a', 'b'],
            ],
            $ast,
            new RootTypeResolver(new TestContainer(), []),
        );

        self::assertSame([TestEnumType::A, TestEnumType::B], $enum);
    }
}
