<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type;

use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\InterfaceTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\BuiltInScalarTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeRegistryFactory;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\FieldResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\InterfaceTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelector;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\TestInterfaceType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class InterfaceTypeResolverTest extends TestCase
{
    private InterfaceTypeResolver $resolver;
    private AstContainer $astContainer;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new InterfaceTypeResolver(
            $this->astContainer = new AstContainer(),
            new BuiltTypesRegistry(),
            new FieldResolver(
                new TestContainer(),
                new DeferredTypeResolver(new TestContainer(), new DeferredTypeRegistryFactory()),
            ),
        );
        $this->resolver->setTypeResolverSelector(new TypeResolverSelector([
            new BuiltInScalarTypeResolver(),
        ]));
    }

    #[Test]
    public function itShouldReturnIfResolverSupportsReference(): void
    {
        $this->astContainer->setAst(new Ast(
            new InterfaceTypeNode(
                TestInterfaceType::class,
                'name',
                null,
                [],
                null,
                [],
            ),
        ));

        self::assertFalse($this->resolver->supports(ScalarTypeReference::create('string')));
        self::assertFalse($this->resolver->supports(ObjectTypeReference::create(TestType::class)));
        self::assertTrue($this->resolver->supports(ObjectTypeReference::create(TestInterfaceType::class)));
    }

    #[Test]
    public function itShouldCreateType(): void
    {
        $this->astContainer->setAst(new Ast(
            new InterfaceTypeNode(
                TestInterfaceType::class,
                'name',
                null,
                [
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'id',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                        null,
                    ),
                ],
                null,
                [],
            ),
        ));

        $type = $this->resolver->createType(ObjectTypeReference::create(TestInterfaceType::class));

        self::assertEquals(new InterfaceType([
            'name' => 'name',
            'description' => null,
            'fields' => [
                [
                    'name' => 'id',
                    'description' => null,
                    'type' => Type::string(),
                    'args' => [],
                    'resolve' => fn() => true,
                ],
            ],
            'resolveType' => fn() => true,
        ]), $type);
    }
}
