<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type;

use GraphQL\Type\Definition\EnumType;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\EnumTypeResolver;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use LogicException;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class EnumTypeResolverTest extends TestCase
{
    private EnumTypeResolver $resolver;
    private AstContainer $astContainer;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new EnumTypeResolver(
            $this->astContainer = new AstContainer(),
        );
    }

    #[Test]
    public function itShouldReturnIfResolverSupportsReference(): void
    {
        $this->astContainer->setAst(new Ast(
            new EnumNode(
                TestEnumType::class,
                'enum',
                null,
                [],
            ),
        ));

        self::assertFalse($this->resolver->supports(ScalarTypeReference::create('string')));
        self::assertFalse($this->resolver->supports(ObjectTypeReference::create(TestType::class)));
        self::assertTrue($this->resolver->supports(ObjectTypeReference::create(TestEnumType::class)));
    }

    #[Test]
    public function itShouldCreateType(): void
    {
        $this->astContainer->setAst(new Ast(
            new EnumNode(
                TestEnumType::class,
                'enum',
                'A description',
                [
                    new EnumValueNode('a', 'Value A', null),
                    new EnumValueNode('b', null, 'Its deprecated'),
                ],
            ),
        ));

        $type = $this->resolver->createType(ObjectTypeReference::create(TestEnumType::class));

        self::assertEquals(new EnumType([
            'name' => 'enum',
            'description' => 'A description',
            'values' => [
                'a' => [
                    'value' => 'a',
                    'description' => 'Value A',
                ],
                'b' => [
                    'value' => 'b',
                    'description' => null,
                    'deprecationReason' => 'Its deprecated',
                ],
            ],
        ]), $type);
    }

    #[Test]
    public function itShouldThrowExceptionOnResolveWhenResultIsNotBackedEnum(): void
    {
        self::expectException(LogicException::class);
        self::expectExceptionMessage('Enum must be a BackedEnum');

        $this->resolver->resolve(ObjectTypeReference::create(TestEnumType::class), fn() => 'invalid');
    }

    #[Test]
    public function itShouldResolve(): void
    {
        $resolved = $this->resolver->resolve(
            ObjectTypeReference::create(TestEnumType::class),
            fn() => TestEnumType::A,
        );

        self::assertSame('a', $resolved);
    }

    #[Test]
    public function itShouldThrowExceptionOnResolveListWhenResultIsNotBackedEnum(): void
    {
        self::expectException(LogicException::class);
        self::expectExceptionMessage('Enum must be a BackedEnum');

        $this->resolver->resolve(
            ObjectTypeReference::create(TestEnumType::class)->setList(),
            fn() => [TestEnumType::A, 'invalid'],
        );
    }

    #[Test]
    public function itShouldResolveList(): void
    {
        $resolved = $this->resolver->resolve(
            ObjectTypeReference::create(TestEnumType::class)->setList(),
            fn() => [TestEnumType::A, TestEnumType::D],
        );

        self::assertSame(['a', 'd'], $resolved);
    }

    #[Test]
    public function itShouldAbstract(): void
    {
        $this->astContainer->setAst(new Ast(
            new EnumNode(
                TestEnumType::class,
                'enum',
                'A description',
                [
                    new EnumValueNode('a', 'Value A', null),
                    new EnumValueNode('b', null, 'Its deprecated'),
                ],
            ),
        ));

        $enum = $this->resolver->abstract(new FieldNode(
            ObjectTypeReference::create(TestEnumType::class),
            'enum',
            null,
            [],
            FieldNodeType::Property,
            null,
            'enum',
            null,
        ), ['enum' => 'a']);

        self::assertSame(TestEnumType::A, $enum);
    }

    #[Test]
    public function itShouldAbstractList(): void
    {
        $this->astContainer->setAst(new Ast(
            new EnumNode(
                TestEnumType::class,
                'enum',
                'A description',
                [
                    new EnumValueNode('a', 'Value A', null),
                    new EnumValueNode('b', null, 'Its deprecated'),
                ],
            ),
        ));

        $enum = $this->resolver->abstract(new FieldNode(
            ObjectTypeReference::create(TestEnumType::class)->setList(),
            'enum',
            null,
            [],
            FieldNodeType::Property,
            null,
            'enum',
            null,
        ), ['enum' => ['a', 'd']]);

        self::assertSame([TestEnumType::A, TestEnumType::D], $enum);
    }
}
