<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeResolver;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestResolvableInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestResolvableType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestResolvableTypeWithEnumAsOutput;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;

/**
 * @internal
 */
final class FieldResolverTest extends TestCase
{
    private FieldResolver $fieldResolver;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->fieldResolver = new FieldResolver();
    }

    #[Test]
    public function itShouldResolveProperty(): void
    {
        $type = $this->fieldResolver->resolve(new FieldNode(
            Type::createScalar('string'),
            'name',
            null,
            [],
            FieldNodeType::Property,
            null,
            'name',
        ), new Ast());

        self::assertSame('a name', $type(new TestResolvableInputType('a name', []), []));
    }

    #[Test]
    public function itShouldResolveMethod(): void
    {
        $type = $this->fieldResolver->resolve(new FieldNode(
            Type::createScalar('string'),
            'name',
            null,
            [
                new ArgNode(
                    Type::createScalar('string'),
                    'name',
                    null,
                    'name',
                ),
            ],
            FieldNodeType::Method,
            'getName',
            null,
        ), new Ast());

        self::assertSame(
            'GetName has been called with name a name',
            $type(new TestResolvableType(), [
                'name' => 'a name',
            ]),
        );
    }

    #[Test]
    public function itShouldResolveMethodWithEnumOutput(): void
    {
        $type = $this->fieldResolver->resolve(new FieldNode(
            Type::createObject(TestEnumType::class),
            'name',
            null,
            [
                new ArgNode(
                    Type::createScalar('string'),
                    'name',
                    null,
                    'name',
                ),
            ],
            FieldNodeType::Method,
            'getName',
            null,
        ), new Ast(
            new EnumNode(
                TestEnumType::class,
                'TestEnum',
                null,
                [
                    new EnumValueNode('a', null),
                    new EnumValueNode('b', null),
                    new EnumValueNode('c', null),
                    new EnumValueNode('d', null),
                ],
            ),
        ));

        self::assertSame(
            'b',
            $type(new TestResolvableTypeWithEnumAsOutput(), [
                'name' => 'b',
            ]),
        );
    }
}
