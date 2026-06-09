<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node\Child;

use DateTime;
use Jerowork\GraphqlAttributeSchema\Node\Child\MapContextNode;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MapContextNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $node = new MapContextNode(
            DateTime::class,
            'aPropertyName',
            true,
        );

        self::assertEquals(MapContextNode::fromArray($node->toArray()), $node);
    }
}
