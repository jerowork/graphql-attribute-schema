<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeResolver;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestResolvableInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestSmallInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestResolvableMutation;
use Jerowork\GraphqlAttributeSchema\Type\DateTimeType;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input\CustomScalarNodeInputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input\EnumNodeInputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input\InputTypeNodeInputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input\ScalarTypeInputChildResolver;
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
            [
                new ScalarTypeInputChildResolver(),
                new CustomScalarNodeInputChildResolver(),
                new EnumNodeInputChildResolver(),
                new InputTypeNodeInputChildResolver(),
            ],
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
                ScalarNodeType::create('string'),
                '__invoke',
                null,
            ),
            new Ast(),
        );
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
                        ScalarNodeType::create('string'),
                        'id',
                        null,
                        'id',
                    ),
                    new ArgNode(
                        ObjectNodeType::create(TestResolvableInputType::class),
                        'input',
                        null,
                        'input',
                    ),
                    new ArgNode(
                        ScalarNodeType::create('string')->setList(),
                        'userIds',
                        null,
                        'userIds',
                    ),
                    new ArgNode(
                        ObjectNodeType::create(TestSmallInputType::class)->setList(),
                        'smallInputs',
                        null,
                        'smallInputs',
                    ),
                    new ArgNode(
                        ObjectNodeType::create(DateTimeImmutable::class),
                        'dateTime',
                        null,
                        'dateTime',
                    ),
                ],
                ScalarNodeType::create('string'),
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
                            ScalarNodeType::create('string'),
                            'name',
                            null,
                            [],
                            FieldNodeType::Property,
                            null,
                            'name',
                            null,
                        ),
                        new FieldNode(
                            ScalarNodeType::create('string')->setList(),
                            'parentNames',
                            null,
                            [],
                            FieldNodeType::Property,
                            null,
                            'parentNames',
                            null,
                        ),
                        new FieldNode(
                            ObjectNodeType::create(DateTimeImmutable::class),
                            'date',
                            null,
                            [],
                            FieldNodeType::Property,
                            null,
                            'date',
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
                            ScalarNodeType::create('string'),
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
                new CustomScalarNode(
                    DateTimeType::class,
                    'DateTime',
                    null,
                    DateTimeImmutable::class,
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
                    'date' => new DateTimeImmutable('2025-01-05 12:23:00'),
                ],
                'userIds' => ['1', '2', '3'],
                'smallInputs' => [
                    ['id' => '4'],
                    ['id' => '5'],
                    ['id' => '6'],
                ],
                'dateTime' => new DateTimeImmutable('2024-12-31 12:00:12'),
            ]),
        );
    }
}
