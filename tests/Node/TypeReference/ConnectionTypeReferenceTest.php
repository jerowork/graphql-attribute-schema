<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node\TypeReference;

use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class ConnectionTypeReferenceTest extends TestCase
{
    #[Test]
    public function itShouldCreateBaseObject(): void
    {
        $reference = ConnectionTypeReference::create(stdClass::class);

        self::assertSame(stdClass::class, $reference->className);
    }

    #[Test]
    public function itShouldSetValueNullable(): void
    {
        $reference = ConnectionTypeReference::create(stdClass::class);

        self::assertFalse($reference->isValueNullable());

        $reference->setNullableValue();

        self::assertTrue($reference->isValueNullable());
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $reference = ConnectionTypeReference::create(stdClass::class)
            ->setNullableValue();

        self::assertEquals(ConnectionTypeReference::fromArray($reference->toArray()), $reference);
    }
}
