<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node;

use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class TypeTest extends TestCase
{
    #[Test]
    public function itShouldCreateBaseScalar(): void
    {
        $type = Type::createScalar('string');

        self::assertSame('string', $type->value);
        self::assertTrue($type->isScalar());
        self::assertFalse($type->isObject());
        self::assertFalse($type->isValueNullable());
        self::assertFalse($type->isList());
        self::assertFalse($type->isListNullable());
    }

    #[Test]
    public function itShouldCreateBaseObject(): void
    {
        $type = Type::createObject(stdClass::class);

        self::assertSame(stdClass::class, $type->value);
        self::assertFalse($type->isScalar());
        self::assertTrue($type->isObject());
        self::assertFalse($type->isValueNullable());
        self::assertFalse($type->isList());
        self::assertFalse($type->isListNullable());
    }

    #[Test]
    public function itShouldSetValueNullable(): void
    {
        $type = Type::createScalar('string');

        self::assertFalse($type->isValueNullable());

        $type->setNullableValue();

        self::assertTrue($type->isValueNullable());
    }

    #[Test]
    public function itShouldSetList(): void
    {
        $type = Type::createScalar('string');

        self::assertFalse($type->isList());

        $type->setList();

        self::assertTrue($type->isList());
    }

    #[Test]
    public function itShouldSetListNullable(): void
    {
        $type = Type::createScalar('string');

        self::assertFalse($type->isListNullable());

        $type->setNullableList();

        self::assertTrue($type->isListNullable());
    }

    #[Test]
    public function itShouldEqual(): void
    {
        $type = Type::createScalar('int')->setList();
        self::assertTrue($type->equals(Type::createScalar('int')->setList()));

        $type2 = Type::createScalar('int')->setList()->setNullableValue()->setNullableList();
        self::assertTrue($type2->equals(Type::createScalar('int')->setList()->setNullableValue()->setNullableList()));

        self::assertFalse($type->equals($type2));
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $typeNode = Type::createObject(stdClass::class)
            ->setList()
            ->setNullableList();

        self::assertEquals(Type::fromArray($typeNode->toArray()), $typeNode);
    }
}
