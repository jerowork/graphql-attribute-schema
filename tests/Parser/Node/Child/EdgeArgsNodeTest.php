<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\EdgeArgsNode;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class EdgeArgsNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $edgeArgsNode = new EdgeArgsNode(
            'service',
        );

        self::assertEquals(EdgeArgsNode::fromArray($edgeArgsNode->toArray()), $edgeArgsNode);
    }
}
