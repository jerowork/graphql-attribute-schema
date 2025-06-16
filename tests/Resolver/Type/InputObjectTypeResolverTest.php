<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Argument\ArgumentNodeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\BuiltInScalarTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeRegistryFactory;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Field\FieldResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\InputObjectTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\ObjectTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelector;
use Jerowork\GraphqlAttributeSchema\Test\AssertSchemaConfig;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestSmallInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class InputObjectTypeResolverTest extends TestCase
{
    private AstContainer $astContainer;
    private TestContainer $container;
    private InputObjectTypeResolver $resolver;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new InputObjectTypeResolver(
            $this->astContainer = new AstContainer(),
            $fieldResolver = new FieldResolver(
                new DeferredTypeResolver(
                    $this->container = new TestContainer(),
                    new DeferredTypeRegistryFactory(),
                ),
                new ArgumentNodeResolver($this->container),
            ),
        );

        $this->resolver->setTypeResolverSelector(new TypeResolverSelector([
            new BuiltInScalarTypeResolver(),
            new ObjectTypeResolver($this->astContainer, $fieldResolver),
        ]));
    }

    #[Test]
    public function itShouldReturnIfResolverSupportsReference(): void
    {
        $this->astContainer->setAst(new Ast(
            new InputTypeNode(
                TestInputType::class,
                'TestInput',
                'Test Input',
                [],
            ),
        ));

        self::assertFalse($this->resolver->supports(ScalarTypeReference::create('string')));
        self::assertFalse($this->resolver->supports(ObjectTypeReference::create(TestType::class)));
        self::assertTrue($this->resolver->supports(ObjectTypeReference::create(TestInputType::class)));
    }

    #[Test]
    public function itShouldCreateType(): void
    {
        $this->astContainer->setAst(new Ast(
            new InputTypeNode(
                TestInputType::class,
                'TestInput',
                'Test Input',
                [
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'value',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'value',
                        null,
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(TestType::class),
                        'test',
                        'Description',
                        [
                            new ArgNode(
                                ScalarTypeReference::create('int')->setNullableValue(),
                                'foo',
                                'A description',
                                'propertyName',
                            ),
                        ],
                        FieldNodeType::Method,
                        'getTest',
                        null,
                        'Its deprecated',
                        null,
                    ),
                ],
            ),
            new TypeNode(
                TestType::class,
                'TestType',
                null,
                [],
                null,
                [],
            ),
        ));

        $type = $this->resolver->createType(ObjectTypeReference::create(TestInputType::class));

        AssertSchemaConfig::assertInputObjectType([
            'name' => 'TestInput',
            'description' => 'Test Input',
            'fields' => [
                [
                    'name' => 'value',
                    'type' => 'String',
                    'description' => null,
                    'deprecationReason' => null,
                ],
                [
                    'name' => 'test',
                    'type' => 'TestType',
                    'description' => 'Description',
                    'deprecationReason' => null,
                ],
            ],
        ], $type);
    }

    #[Test]
    public function itShouldResolve(): void
    {
        $resolved = $this->resolver->resolve(
            ObjectTypeReference::create(TestInputType::class),
            fn() => 'resolved',
        );

        self::assertSame('resolved', $resolved);
    }

    #[Test]
    public function itShouldAbstract(): void
    {
        $this->astContainer->setAst(new Ast(
            new InputTypeNode(
                TestSmallInputType::class,
                'TestInput',
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
            ),
        ));

        $abstract = $this->resolver->abstract(new FieldNode(
            ObjectTypeReference::create(TestSmallInputType::class),
            'smallInput',
            null,
            [],
            FieldNodeType::Property,
            null,
            'smallInput',
            null,
            null,
        ), [
            'smallInput' => ['id' => 'identifier'],
        ]);

        self::assertEquals(new TestSmallInputType('identifier'), $abstract);
    }

    #[Test]
    public function itShouldAbstractWithAbsentArgument(): void
    {
        $this->astContainer->setAst(new Ast(
            new InputTypeNode(
                TestSmallInputType::class,
                'TestInput',
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
            ),
        ));

        $abstract = $this->resolver->abstract(new FieldNode(
            ObjectTypeReference::create(TestSmallInputType::class),
            'smallInput',
            null,
            [],
            FieldNodeType::Property,
            null,
            'smallInput',
            null,
            null,
        ), []);

        self::assertNull($abstract);
    }

    #[Test]
    public function itShouldAbstractWithNullArgument(): void
    {
        $this->astContainer->setAst(new Ast(
            new InputTypeNode(
                TestSmallInputType::class,
                'TestInput',
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
            ),
        ));

        $abstract = $this->resolver->abstract(new FieldNode(
            ObjectTypeReference::create(TestSmallInputType::class),
            'smallInput',
            null,
            [],
            FieldNodeType::Property,
            null,
            'smallInput',
            null,
            null,
        ), [
            'smallInput' => null,
        ]);

        self::assertNull($abstract);
    }
}
