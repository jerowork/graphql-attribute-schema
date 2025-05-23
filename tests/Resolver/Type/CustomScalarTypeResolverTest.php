<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type;

use GraphQL\Type\Definition\CustomScalarType;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\CustomScalarTypeResolver;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\Type\DateTimeType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CustomScalarTypeResolverTest extends TestCase
{
    private CustomScalarTypeResolver $resolver;
    private AstContainer $astContainer;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new CustomScalarTypeResolver(
            $this->astContainer = new AstContainer(),
        );
    }

    #[Test]
    public function itShouldReturnIfResolverSupportsReference(): void
    {
        $this->astContainer->setAst(new Ast(
            new ScalarNode(
                DateTimeType::class,
                'date',
                null,
                null,
            ),
        ));

        self::assertFalse($this->resolver->supports(ScalarTypeReference::create('string')));
        self::assertFalse($this->resolver->supports(ObjectTypeReference::create(TestType::class)));
        self::assertTrue($this->resolver->supports(ObjectTypeReference::create(DateTimeType::class)));
    }

    #[Test]
    public function itShouldCreateType(): void
    {
        $this->astContainer->setAst(new Ast(
            new ScalarNode(
                DateTimeType::class,
                'date',
                'Date Time',
                null,
            ),
        ));

        $type = $this->resolver->createType(ObjectTypeReference::create(DateTimeType::class));

        self::assertEquals(new CustomScalarType([
            'name' => 'date',
            'serialize' => fn() => true,
            'parseValue' => fn() => true,
            'parseLiteral' => fn() => true,
            'description' => 'Date Time',
        ]), $type);
    }

    #[Test]
    public function itShouldResolve(): void
    {
        $resolved = $this->resolver->resolve(
            ObjectTypeReference::create(DateTimeType::class),
            fn() => '2025-02-07T12:00:12+00:00',
        );

        self::assertSame('2025-02-07T12:00:12+00:00', $resolved);
    }

    #[Test]
    public function itShouldResolveList(): void
    {
        $resolved = $this->resolver->resolve(
            ObjectTypeReference::create(DateTimeType::class),
            fn() => ['2025-02-07T12:00:12+00:00', '2025-01-23T13:14:23+00:00'],
        );

        self::assertSame(['2025-02-07T12:00:12+00:00', '2025-01-23T13:14:23+00:00'], $resolved);
    }

    #[Test]
    public function itShouldAbstract(): void
    {
        $value = $this->resolver->abstract(new FieldNode(
            ObjectTypeReference::create(DateTimeType::class),
            'value',
            null,
            [],
            FieldNodeType::Property,
            null,
            'value',
            null,
        ), ['value' => '2025-02-07T12:00:12+00:00']);

        self::assertSame('2025-02-07T12:00:12+00:00', $value);
    }

    #[Test]
    public function itShouldAbstractNullable(): void
    {
        $value = $this->resolver->abstract(new FieldNode(
            ObjectTypeReference::create(DateTimeType::class),
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
