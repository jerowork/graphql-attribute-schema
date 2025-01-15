<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Reference;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ConnectionReference;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class ConnectionReferenceTest extends TestCase
{
    #[Test]
    public function itShouldCreateBaseObject(): void
    {
        $reference = ConnectionReference::create(stdClass::class, 10);

        self::assertSame(stdClass::class, $reference->className);
    }

    #[Test]
    public function itShouldSetValueNullable(): void
    {
        $reference = ConnectionReference::create(stdClass::class, 10);

        self::assertFalse($reference->isValueNullable());

        $reference->setNullableValue();

        self::assertTrue($reference->isValueNullable());
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $reference = ConnectionReference::create(stdClass::class, 10)
            ->setNullableValue();

        self::assertEquals(ConnectionReference::fromArray($reference->toArray()), $reference);
    }
}
