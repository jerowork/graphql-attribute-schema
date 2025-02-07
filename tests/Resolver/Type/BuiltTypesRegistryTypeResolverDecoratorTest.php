<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type;

use DateTimeImmutable;
use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\ScalarType;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\BuiltTypesRegistryTypeResolverDecorator;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Resolver\Type\TestTypeResolver;
use Jerowork\GraphqlAttributeSchema\Type\DateTimeType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class BuiltTypesRegistryTypeResolverDecoratorTest extends TestCase
{
    private BuiltTypesRegistryTypeResolverDecorator $decorator;
    private AstContainer $astContainer;
    private TestTypeResolver $typeResolver;
    private BuiltTypesRegistry $registry;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->decorator = new BuiltTypesRegistryTypeResolverDecorator(
            $this->astContainer = new AstContainer(),
            $this->typeResolver = new TestTypeResolver(),
            $this->registry = new BuiltTypesRegistry(),
        );
    }

    #[Test]
    public function itShouldIgnoreIfReferenceIsNotObjectReference(): void
    {
        $this->typeResolver->createdType = ScalarType::boolean();

        $type = $this->decorator->createType(ScalarTypeReference::create('bool'));

        self::assertSame($this->typeResolver->createdType, $type);
    }

    #[Test]
    public function itShouldCreateTypeViaInnerResolverAndAddToRegistry(): void
    {
        $this->astContainer->setAst(new Ast(
            new ScalarNode(
                DateTimeType::class,
                'date',
                'Date Time',
                null,
            ),
        ));

        $this->typeResolver->createdType = new CustomScalarType([
            'name' => 'date',
            'serialize' => fn() => true,
            'parseValue' => fn() => true,
            'parseLiteral' => fn() => true,
            'description' => 'Date Time',
        ]);

        $type = $this->decorator->createType(ObjectTypeReference::create(DateTimeType::class));

        self::assertSame($this->typeResolver->createdType, $type);
        self::assertTrue($this->typeResolver->createTypeIsCalled);

        self::assertSame($this->typeResolver->createdType, $this->registry->getType(DateTimeType::class));
    }

    #[Test]
    public function itShouldAddToRegistryByAlias(): void
    {
        $this->astContainer->setAst(new Ast(
            new ScalarNode(
                DateTimeType::class,
                'date',
                'Date Time',
                DateTimeImmutable::class,
            ),
        ));

        $this->typeResolver->createdType = new CustomScalarType([
            'name' => 'date',
            'serialize' => fn() => true,
            'parseValue' => fn() => true,
            'parseLiteral' => fn() => true,
            'description' => 'Date Time',
        ]);

        $type = $this->decorator->createType(ObjectTypeReference::create(DateTimeType::class));

        self::assertSame($this->typeResolver->createdType, $type);
        self::assertTrue($this->typeResolver->createTypeIsCalled);

        self::assertSame($this->typeResolver->createdType, $this->registry->getType(DateTimeImmutable::class));
    }

    #[Test]
    public function itShouldReturnTypeFromRegistryIfAlreadyCreatedBefore(): void
    {
        $this->astContainer->setAst(new Ast(
            new ScalarNode(
                DateTimeType::class,
                'date',
                'Date Time',
                null,
            ),
        ));

        $expectedType = new CustomScalarType([
            'name' => 'date',
            'serialize' => fn() => true,
            'parseValue' => fn() => true,
            'parseLiteral' => fn() => true,
            'description' => 'Date Time',
        ]);

        $this->registry->addType(DateTimeType::class, $expectedType);

        $type = $this->decorator->createType(ObjectTypeReference::create(DateTimeType::class));

        self::assertSame($expectedType, $type);
        self::assertFalse($this->typeResolver->createTypeIsCalled);

        self::assertSame($expectedType, $this->registry->getType(DateTimeType::class));
    }

    #[Test]
    public function itShouldGetFromRegistryByAlias(): void
    {
        $this->astContainer->setAst(new Ast(
            new ScalarNode(
                DateTimeType::class,
                'date',
                'Date Time',
                DateTimeImmutable::class,
            ),
        ));

        $expectedType = new CustomScalarType([
            'name' => 'date',
            'serialize' => fn() => true,
            'parseValue' => fn() => true,
            'parseLiteral' => fn() => true,
            'description' => 'Date Time',
        ]);

        $this->registry->addType(DateTimeImmutable::class, $expectedType);

        $type = $this->decorator->createType(ObjectTypeReference::create(DateTimeType::class));

        self::assertSame($expectedType, $type);
        self::assertFalse($this->typeResolver->createTypeIsCalled);

        self::assertSame($expectedType, $this->registry->getType(DateTimeImmutable::class));
    }
}
