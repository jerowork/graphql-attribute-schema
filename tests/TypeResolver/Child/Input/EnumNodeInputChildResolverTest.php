<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeResolver\Child\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestScalarType;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input\EnumNodeInputChildResolver;
use Override;

/**
 * @internal
 */
final class EnumNodeInputChildResolverTest extends TestCase
{
    private EnumNodeInputChildResolver $resolver;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new EnumNodeInputChildResolver();
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

        $enum = $this->resolver->resolve(
            new FieldNode(
                ObjectNodeType::create(TestEnumType::class),
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
                ObjectNodeType::create(TestEnumType::class)->setList(),
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
