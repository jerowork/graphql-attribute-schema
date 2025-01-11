<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Reference;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class ScalarReferenceTest extends TestCase
{
    #[Test]
    public function itShouldCreateBaseScalar(): void
    {
        $type = ScalarReference::create('string');

        self::assertSame('string', $type->value);
        self::assertFalse($type->isValueNullable());
        self::assertFalse($type->isList());
        self::assertFalse($type->isListNullable());
    }

    #[Test]
    public function itShouldSetValueNullable(): void
    {
        $type = ScalarReference::create('string');

        self::assertFalse($type->isValueNullable());

        $type->setNullableValue();

        self::assertTrue($type->isValueNullable());
    }

    #[Test]
    public function itShouldSetList(): void
    {
        $type = ScalarReference::create('string');

        self::assertFalse($type->isList());

        $type->setList();

        self::assertTrue($type->isList());
    }

    #[Test]
    public function itShouldSetListNullable(): void
    {
        $type = ScalarReference::create('string');

        self::assertFalse($type->isListNullable());

        $type->setNullableList();

        self::assertTrue($type->isListNullable());
    }

    #[Test]
    public function itShouldEqual(): void
    {
        $type = ScalarReference::create('int')->setList();
        self::assertTrue($type->equals(ScalarReference::create('int')->setList()));

        $type2 = ScalarReference::create('int')->setList()->setNullableValue()->setNullableList();
        self::assertTrue($type2->equals(ScalarReference::create('int')->setList()->setNullableValue()->setNullableList()));

        self::assertFalse($type->equals($type2));
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $typeNode = ScalarReference::create(stdClass::class)
            ->setList()
            ->setNullableList();

        self::assertEquals(ScalarReference::fromArray($typeNode->toArray()), $typeNode);
    }
}
