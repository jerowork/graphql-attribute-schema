<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeResolver;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestResolvableInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestResolvableType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestResolvableTypeWithEnumAsOutput;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\EnumNodeOutputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\ScalarTypeOutputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;
use DateTimeImmutable;

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

        $this->fieldResolver = new FieldResolver(
            new TestContainer(),
            [
                new ScalarTypeOutputChildResolver(),
                new EnumNodeOutputChildResolver(),
            ],
        );
    }

    #[Test]
    public function itShouldResolveProperty(): void
    {
        $type = $this->fieldResolver->resolve(new FieldNode(
            ScalarNodeType::create('string'),
            'name',
            null,
            [],
            FieldNodeType::Property,
            null,
            'name',
            null,
        ), new Ast());

        self::assertSame('a name', $type(new TestResolvableInputType('a name', [], new DateTimeImmutable()), []));
    }

    #[Test]
    public function itShouldResolveMethod(): void
    {
        $type = $this->fieldResolver->resolve(new FieldNode(
            ScalarNodeType::create('string'),
            'name',
            null,
            [
                new ArgNode(
                    ScalarNodeType::create('string'),
                    'name',
                    null,
                    'name',
                ),
            ],
            FieldNodeType::Method,
            'getName',
            null,
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
            ObjectNodeType::create(TestEnumType::class),
            'name',
            null,
            [
                new ArgNode(
                    ScalarNodeType::create('string'),
                    'name',
                    null,
                    'name',
                ),
            ],
            FieldNodeType::Method,
            'getName',
            null,
            null,
        ), new Ast(
            new EnumNode(
                TestEnumType::class,
                'TestEnum',
                null,
                [
                    new EnumValueNode('a', null, null),
                    new EnumValueNode('b', null, null),
                    new EnumValueNode('c', null, null),
                    new EnumValueNode('d', null, null),
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
