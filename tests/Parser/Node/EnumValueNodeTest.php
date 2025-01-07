<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node;

use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumValueNode;
use PHPUnit\Framework\Attributes\Test;

/**
 * @internal
 */
final class EnumValueNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $enumValueNode = new EnumValueNode(
            'value',
            'a description',
            'deprecated',
        );

        self::assertEquals(EnumValueNode::fromArray($enumValueNode->toArray()), $enumValueNode);
    }
}
