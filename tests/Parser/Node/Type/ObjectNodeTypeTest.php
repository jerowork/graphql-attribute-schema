<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Type;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class ObjectNodeTypeTest extends TestCase
{
    #[Test]
    public function itShouldCreateBaseObject(): void
    {
        $type = ObjectNodeType::create(stdClass::class);

        self::assertSame(stdClass::class, $type->className);
        self::assertFalse($type->isValueNullable());
        self::assertFalse($type->isList());
        self::assertFalse($type->isListNullable());
    }

    #[Test]
    public function itShouldSetValueNullable(): void
    {
        $type = ObjectNodeType::create(stdClass::class);

        self::assertFalse($type->isValueNullable());

        $type->setNullableValue();

        self::assertTrue($type->isValueNullable());
    }

    #[Test]
    public function itShouldSetList(): void
    {
        $type = ObjectNodeType::create(stdClass::class);

        self::assertFalse($type->isList());

        $type->setList();

        self::assertTrue($type->isList());
    }

    #[Test]
    public function itShouldSetListNullable(): void
    {
        $type = ObjectNodeType::create(stdClass::class);

        self::assertFalse($type->isListNullable());

        $type->setNullableList();

        self::assertTrue($type->isListNullable());
    }

    #[Test]
    public function itShouldEqual(): void
    {
        $type = ObjectNodeType::create(stdClass::class)->setList();
        self::assertTrue($type->equals(ObjectNodeType::create(stdClass::class)->setList()));

        $type2 = ObjectNodeType::create(stdClass::class)->setList()->setNullableValue()->setNullableList();
        self::assertTrue($type2->equals(ObjectNodeType::create(stdClass::class)->setList()->setNullableValue()->setNullableList()));

        self::assertFalse($type->equals($type2));
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $typeNode = ObjectNodeType::create(stdClass::class)
            ->setList()
            ->setNullableList();

        self::assertEquals(ObjectNodeType::fromArray($typeNode->toArray()), $typeNode);
    }
}
