<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node\Child;

use Jerowork\GraphqlAttributeSchema\Node\Child\ContextNode;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ContextNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $node = new ContextNode('aPropertyName');

        self::assertEquals(ContextNode::fromArray($node->toArray()), $node);
    }
}
