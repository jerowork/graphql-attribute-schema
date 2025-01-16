<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\TypeReference;

use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ConnectionTypeReference;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class ConnectionTypeReferenceTest extends TestCase
{
    #[Test]
    public function itShouldCreateBaseObject(): void
    {
        $reference = ConnectionTypeReference::create(stdClass::class, 10);

        self::assertSame(stdClass::class, $reference->className);
    }

    #[Test]
    public function itShouldSetValueNullable(): void
    {
        $reference = ConnectionTypeReference::create(stdClass::class, 10);

        self::assertFalse($reference->isValueNullable());

        $reference->setNullableValue();

        self::assertTrue($reference->isValueNullable());
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $reference = ConnectionTypeReference::create(stdClass::class, 10)
            ->setNullableValue();

        self::assertEquals(ConnectionTypeReference::fromArray($reference->toArray()), $reference);
    }
}
