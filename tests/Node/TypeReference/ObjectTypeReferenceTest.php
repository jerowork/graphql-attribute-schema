<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node\TypeReference;

use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class ObjectTypeReferenceTest extends TestCase
{
    #[Test]
    public function itShouldCreateBaseObject(): void
    {
        $type = ObjectTypeReference::create(stdClass::class);

        self::assertSame(stdClass::class, $type->className);
        self::assertFalse($type->isValueNullable());
        self::assertFalse($type->isList());
        self::assertFalse($type->isListNullable());
    }

    #[Test]
    public function itShouldSetValueNullable(): void
    {
        $type = ObjectTypeReference::create(stdClass::class);

        self::assertFalse($type->isValueNullable());

        $type->setNullableValue();

        self::assertTrue($type->isValueNullable());
    }

    #[Test]
    public function itShouldSetList(): void
    {
        $type = ObjectTypeReference::create(stdClass::class);

        self::assertFalse($type->isList());

        $type->setList();

        self::assertTrue($type->isList());
    }

    #[Test]
    public function itShouldSetListNullable(): void
    {
        $type = ObjectTypeReference::create(stdClass::class);

        self::assertFalse($type->isListNullable());

        $type->setNullableList();

        self::assertTrue($type->isListNullable());
    }

    #[Test]
    public function itShouldEqual(): void
    {
        $type = ObjectTypeReference::create(stdClass::class)->setList();
        self::assertTrue($type->equals(ObjectTypeReference::create(stdClass::class)->setList()));

        $type2 = ObjectTypeReference::create(stdClass::class)->setList()->setNullableValue()->setNullableList();
        self::assertTrue($type2->equals(ObjectTypeReference::create(stdClass::class)->setList()->setNullableValue()->setNullableList()));

        self::assertFalse($type->equals($type2));
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $typeNode = ObjectTypeReference::create(stdClass::class)
            ->setList()
            ->setNullableList();

        self::assertEquals(ObjectTypeReference::fromArray($typeNode->toArray()), $typeNode);
    }
}
