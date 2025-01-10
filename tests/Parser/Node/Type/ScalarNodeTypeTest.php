<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Type;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class ScalarNodeTypeTest extends TestCase
{
    #[Test]
    public function itShouldCreateBaseScalar(): void
    {
        $type = ScalarNodeType::create('string');

        self::assertSame('string', $type->value);
        self::assertFalse($type->isValueNullable());
        self::assertFalse($type->isList());
        self::assertFalse($type->isListNullable());
    }

    #[Test]
    public function itShouldSetValueNullable(): void
    {
        $type = ScalarNodeType::create('string');

        self::assertFalse($type->isValueNullable());

        $type->setNullableValue();

        self::assertTrue($type->isValueNullable());
    }

    #[Test]
    public function itShouldSetList(): void
    {
        $type = ScalarNodeType::create('string');

        self::assertFalse($type->isList());

        $type->setList();

        self::assertTrue($type->isList());
    }

    #[Test]
    public function itShouldSetListNullable(): void
    {
        $type = ScalarNodeType::create('string');

        self::assertFalse($type->isListNullable());

        $type->setNullableList();

        self::assertTrue($type->isListNullable());
    }

    #[Test]
    public function itShouldEqual(): void
    {
        $type = ScalarNodeType::create('int')->setList();
        self::assertTrue($type->equals(ScalarNodeType::create('int')->setList()));

        $type2 = ScalarNodeType::create('int')->setList()->setNullableValue()->setNullableList();
        self::assertTrue($type2->equals(ScalarNodeType::create('int')->setList()->setNullableValue()->setNullableList()));

        self::assertFalse($type->equals($type2));
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $typeNode = ScalarNodeType::create(stdClass::class)
            ->setList()
            ->setNullableList();

        self::assertEquals(ScalarNodeType::fromArray($typeNode->toArray()), $typeNode);
    }
}
