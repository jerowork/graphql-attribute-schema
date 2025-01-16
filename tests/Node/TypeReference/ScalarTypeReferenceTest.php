<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node\TypeReference;

use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class ScalarTypeReferenceTest extends TestCase
{
    #[Test]
    public function itShouldCreateBaseScalar(): void
    {
        $type = ScalarTypeReference::create('string');

        self::assertSame('string', $type->value);
        self::assertFalse($type->isValueNullable());
        self::assertFalse($type->isList());
        self::assertFalse($type->isListNullable());
    }

    #[Test]
    public function itShouldSetValueNullable(): void
    {
        $type = ScalarTypeReference::create('string');

        self::assertFalse($type->isValueNullable());

        $type->setNullableValue();

        self::assertTrue($type->isValueNullable());
    }

    #[Test]
    public function itShouldSetList(): void
    {
        $type = ScalarTypeReference::create('string');

        self::assertFalse($type->isList());

        $type->setList();

        self::assertTrue($type->isList());
    }

    #[Test]
    public function itShouldSetListNullable(): void
    {
        $type = ScalarTypeReference::create('string');

        self::assertFalse($type->isListNullable());

        $type->setNullableList();

        self::assertTrue($type->isListNullable());
    }

    #[Test]
    public function itShouldEqual(): void
    {
        $type = ScalarTypeReference::create('int')->setList();
        self::assertTrue($type->equals(ScalarTypeReference::create('int')->setList()));

        $type2 = ScalarTypeReference::create('int')->setList()->setNullableValue()->setNullableList();
        self::assertTrue($type2->equals(ScalarTypeReference::create('int')->setList()->setNullableValue()->setNullableList()));

        self::assertFalse($type->equals($type2));
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $typeNode = ScalarTypeReference::create(stdClass::class)
            ->setList()
            ->setNullableList();

        self::assertEquals(ScalarTypeReference::fromArray($typeNode->toArray()), $typeNode);
    }
}
