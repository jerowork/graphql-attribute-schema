<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Reference;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class ObjectReferenceTest extends TestCase
{
    #[Test]
    public function itShouldCreateBaseObject(): void
    {
        $type = ObjectReference::create(stdClass::class);

        self::assertSame(stdClass::class, $type->className);
        self::assertFalse($type->isValueNullable());
        self::assertFalse($type->isList());
        self::assertFalse($type->isListNullable());
    }

    #[Test]
    public function itShouldSetValueNullable(): void
    {
        $type = ObjectReference::create(stdClass::class);

        self::assertFalse($type->isValueNullable());

        $type->setNullableValue();

        self::assertTrue($type->isValueNullable());
    }

    #[Test]
    public function itShouldSetList(): void
    {
        $type = ObjectReference::create(stdClass::class);

        self::assertFalse($type->isList());

        $type->setList();

        self::assertTrue($type->isList());
    }

    #[Test]
    public function itShouldSetListNullable(): void
    {
        $type = ObjectReference::create(stdClass::class);

        self::assertFalse($type->isListNullable());

        $type->setNullableList();

        self::assertTrue($type->isListNullable());
    }

    #[Test]
    public function itShouldEqual(): void
    {
        $type = ObjectReference::create(stdClass::class)->setList();
        self::assertTrue($type->equals(ObjectReference::create(stdClass::class)->setList()));

        $type2 = ObjectReference::create(stdClass::class)->setList()->setNullableValue()->setNullableList();
        self::assertTrue($type2->equals(ObjectReference::create(stdClass::class)->setList()->setNullableValue()->setNullableList()));

        self::assertFalse($type->equals($type2));
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $typeNode = ObjectReference::create(stdClass::class)
            ->setList()
            ->setNullableList();

        self::assertEquals(ObjectReference::fromArray($typeNode->toArray()), $typeNode);
    }
}
