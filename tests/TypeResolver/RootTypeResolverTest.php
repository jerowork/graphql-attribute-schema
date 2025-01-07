<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeResolver;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestResolvableInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestSmallInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestResolvableMutation;
use Jerowork\GraphqlAttributeSchema\TypeResolver\ResolveException;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;

/**
 * @internal
 */
final class RootTypeResolverTest extends TestCase
{
    private TestContainer $container;
    private RootTypeResolver $rootTypeResolver;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->rootTypeResolver = new RootTypeResolver(
            $this->container = new TestContainer(),
        );
    }

    #[Test]
    public function itShouldGuardIfNodeTypeIdIsInContainer(): void
    {
        self::expectException(ResolveException::class);
        self::expectExceptionMessage('Node type ID ' . TestResolvableMutation::class . ' is not in a container');

        $this->rootTypeResolver->resolve(
            new MutationNode(
                TestResolvableMutation::class,
                'Test',
                null,
                [],
                Type::createScalar('string'),
                '__invoke',
                null,
            ),
            new Ast(),
        );
    }

    #[Test]
    public function itShouldGuardIfNodeIsInAst(): void
    {
        $this->container->set(TestResolvableMutation::class, new TestResolvableMutation());

        self::expectException(ResolveException::class);
        self::expectExceptionMessage('Node not found for typeId ' . TestResolvableInputType::class);

        $type = $this->rootTypeResolver->resolve(
            new MutationNode(
                TestResolvableMutation::class,
                'Test',
                null,
                [
                    new ArgNode(
                        Type::createScalar('string'),
                        'id',
                        null,
                        'id',
                    ),
                    new ArgNode(
                        Type::createObject(TestResolvableInputType::class),
                        'input',
                        null,
                        'input',
                    ),
                ],
                Type::createScalar('string'),
                '__invoke',
                null,
            ),
            new Ast(),
        );

        $type('rootValue', [
            'id' => '45963d07-796c-44d5-8f1b-5e92ae6225a9',
            'input' => [
                'name' => 'Foobar',
            ],
        ]);
    }

    #[Test]
    public function itShouldResolve(): void
    {
        $this->container->set(TestResolvableMutation::class, new TestResolvableMutation());

        $type = $this->rootTypeResolver->resolve(
            new MutationNode(
                TestResolvableMutation::class,
                'Test',
                null,
                [
                    new ArgNode(
                        Type::createScalar('string'),
                        'id',
                        null,
                        'id',
                    ),
                    new ArgNode(
                        Type::createObject(TestResolvableInputType::class),
                        'input',
                        null,
                        'input',
                    ),
                    new ArgNode(
                        Type::createScalar('string')->setList(),
                        'userIds',
                        null,
                        'userIds',
                    ),
                    new ArgNode(
                        Type::createObject(TestSmallInputType::class)->setList(),
                        'smallInputs',
                        null,
                        'smallInputs',
                    ),
                ],
                Type::createScalar('string'),
                '__invoke',
                null,
            ),
            new Ast(
                new InputTypeNode(
                    TestResolvableInputType::class,
                    'TestResolvableInput',
                    null,
                    [
                        new FieldNode(
                            Type::createScalar('string'),
                            'name',
                            null,
                            [],
                            FieldNodeType::Property,
                            null,
                            'name',
                            null,
                        ),
                        new FieldNode(
                            Type::createScalar('string')->setList(),
                            'parentNames',
                            null,
                            [],
                            FieldNodeType::Property,
                            null,
                            'parentNames',
                            null,
                        ),
                    ],
                ),
                new InputTypeNode(
                    TestSmallInputType::class,
                    'TestSmallInput',
                    null,
                    [
                        new FieldNode(
                            Type::createScalar('string'),
                            'id',
                            null,
                            [],
                            FieldNodeType::Property,
                            null,
                            'id',
                            null,
                        ),
                    ],
                ),
            ),
        );

        self::assertSame(
            'Mutation has been called with id 45963d07-796c-44d5-8f1b-5e92ae6225a9 and input with name Foobar, userIds: 1, 2, 3, parentNames: John, Jane, smallInputs: 4, 5, 6',
            $type('rootValue', [
                'id' => '45963d07-796c-44d5-8f1b-5e92ae6225a9',
                'input' => [
                    'name' => 'Foobar',
                    'parentNames' => ['John', 'Jane'],
                ],
                'userIds' => ['1', '2', '3'],
                'smallInputs' => [
                    ['id' => '4'],
                    ['id' => '5'],
                    ['id' => '6'],
                ],
            ]),
        );
    }
}
