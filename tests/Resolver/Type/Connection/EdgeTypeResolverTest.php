<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type\Connection;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Connection\EdgeTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeRegistryFactory;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\FieldResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\ObjectTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelector;
use Jerowork\GraphqlAttributeSchema\Test\AssertSchemaConfig;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestScalarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestAnother;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use LogicException;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class EdgeTypeResolverTest extends TestCase
{
    private EdgeTypeResolver $resolver;
    private BuiltTypesRegistry $builtTypesRegistry;
    private AstContainer $astContainer;
    private FieldResolver $fieldResolver;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new EdgeTypeResolver(
            $this->astContainer = new AstContainer(),
            $this->builtTypesRegistry = new BuiltTypesRegistry(),
            $this->fieldResolver = new FieldResolver(
                new TestContainer(),
                new DeferredTypeResolver(new TestContainer(), new DeferredTypeRegistryFactory()),
            ),
        );
    }

    #[Test]
    public function itShouldCreateEdgeTypeAndStoreInRegistry(): void
    {
        $this->astContainer->setAst(new Ast(
            new ScalarNode(
                TestScalarType::class,
                'TestScalar',
                null,
                null,
            ),
            $typeNode = new TypeNode(
                TestType::class,
                'Test',
                null,
                [],
                new CursorNode(
                    ObjectTypeReference::create(TestScalarType::class),
                    FieldNodeType::Property,
                    null,
                    'property',
                ),
                [],
            ),
        ));

        self::assertFalse($this->builtTypesRegistry->hasType('TestEdge'));

        $edgeType = $this->resolver->createEdgeType(
            ConnectionTypeReference::create(TestType::class),
            $typeNode,
            new TypeResolverSelector([
                new ObjectTypeResolver($this->astContainer, $this->fieldResolver),
            ]),
        );

        self::assertTrue($this->builtTypesRegistry->hasType('TestEdge'));

        self::assertSame($this->builtTypesRegistry->getType('TestEdge'), $edgeType);
        self::assertInstanceOf(NonNull::class, $edgeType);

        AssertSchemaConfig::assertObjectType([
            'name' => 'TestEdge',
            'description' => null,
            'fields' => [
                [
                    'name' => 'node',
                    'type' => 'Test',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
                [
                    'name' => 'cursor',
                    'type' => 'String!',
                    'description' => null,
                    'deprecationReason' => null,
                    'args' => [],
                ],
            ],
        ], $edgeType->getWrappedType());
    }

    #[Test]
    public function itShouldGuardThatCursorIsCustomScalarWhenItsAnObjectType(): void
    {
        $this->astContainer->setAst(new Ast(
            new TypeNode(
                TestAnother::class,
                'TestAnother',
                null,
                [],
                null,
                [],
            ),
            $typeNode = new TypeNode(
                TestType::class,
                'Test',
                null,
                [],
                new CursorNode(
                    ObjectTypeReference::create(TestAnother::class),
                    FieldNodeType::Property,
                    null,
                    'property',
                ),
                [],
            ),
        ));

        self::expectException(LogicException::class);
        self::expectExceptionMessage('Invalid object type cursor connection edge type');

        $this->resolver->createEdgeType(
            ConnectionTypeReference::create(TestType::class),
            $typeNode,
            new TypeResolverSelector([
                new ObjectTypeResolver($this->astContainer, $this->fieldResolver),
            ]),
        );
    }

    #[Test]
    public function itShouldGetEdgeTypeFromRegistry(): void
    {
        $this->builtTypesRegistry->addType('TestEdge', $expectedType = Type::nonNull(new ObjectType([
            'name' => 'TestEdge',
            'fields' => [
                [
                    'name' => 'node',
                    'type' => new ObjectType([
                        'name' => 'Test',
                        'description' => null,
                        'fields' => [],
                    ]),
                    'resolve' => fn($objectValue) => $objectValue,
                ],
                [
                    'name' => 'cursor',
                    'type' => Type::string(),
                    'resolve' => fn() => true,
                ],
            ],
        ])));

        self::assertTrue($this->builtTypesRegistry->hasType('TestEdge'));

        $edgeType = $this->resolver->createEdgeType(
            ConnectionTypeReference::create(TestType::class),
            new TypeNode(
                TestType::class,
                'Test',
                null,
                [],
                null,
                [],
            ),
            new TypeResolverSelector([]),
        );

        self::assertSame($expectedType, $edgeType);
    }
}
