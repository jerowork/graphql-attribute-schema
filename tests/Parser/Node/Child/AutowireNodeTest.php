<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\AutowireNode;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class AutowireNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $autowireNode = new AutowireNode(
            stdClass::class,
            'service',
        );

        self::assertEquals(AutowireNode::fromArray($autowireNode->toArray()), $autowireNode);
    }
}
