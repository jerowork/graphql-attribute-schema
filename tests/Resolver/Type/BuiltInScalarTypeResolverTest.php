<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\BuiltInScalarTypeResolver;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use LogicException;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class BuiltInScalarTypeResolverTest extends TestCase
{
    private BuiltInScalarTypeResolver $resolver;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new BuiltInScalarTypeResolver();
    }

    #[Test]
    public function itShouldReturnIfResolverSupportsReference(): void
    {
        self::assertTrue($this->resolver->supports(ScalarTypeReference::create('string')));
        self::assertFalse($this->resolver->supports(ObjectTypeReference::create(TestType::class)));
    }

    #[Test]
    public function itShouldCreateType(): void
    {
        $type = $this->resolver->createType(ScalarTypeReference::create('float'));

        self::assertEquals(Type::float(), $type);
    }

    #[Test]
    public function itShouldThrowExceptionOnCreateTypeWhenValueIsInvalid(): void
    {
        self::expectException(LogicException::class);

        $this->resolver->createType(ScalarTypeReference::create('invalid'));
    }

    #[Test]
    public function itShouldResolve(): void
    {
        $resolved = $this->resolver->resolve(ScalarTypeReference::create('string'), fn() => 'test-string');

        self::assertSame('test-string', $resolved);
    }

    #[Test]
    public function itShouldResolveList(): void
    {
        $resolved = $this->resolver->resolve(
            ScalarTypeReference::create('string')->setList(),
            fn() => ['test-string', 'another-string'],
        );

        self::assertSame(['test-string', 'another-string'], $resolved);
    }

    #[Test]
    public function itShouldAbstract(): void
    {
        $value = $this->resolver->abstract(new FieldNode(
            ScalarTypeReference::create('string'),
            'value',
            null,
            [],
            FieldNodeType::Property,
            null,
            'value',
            null,
        ), ['value' => 'test-string']);

        self::assertSame('test-string', $value);
    }

    #[Test]
    public function itShouldAbstractNullable(): void
    {
        $value = $this->resolver->abstract(new FieldNode(
            ScalarTypeReference::create('string'),
            'value',
            null,
            [],
            FieldNodeType::Property,
            null,
            'value',
            null,
        ), []);

        self::assertNull($value);
    }
}
