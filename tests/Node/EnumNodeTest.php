<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node;

use Jerowork\GraphqlAttributeSchema\Node\Child\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use PHPUnit\Framework\Attributes\Test;

/**
 * @internal
 */
final class EnumNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $enumNode = new EnumNode(
            TestEnumType::class,
            'enum',
            'description',
            [
                new EnumValueNode(
                    'value',
                    'a description',
                    'deprecated',
                ),
                new EnumValueNode(
                    'value2',
                    'b description',
                    'deprecated',
                ),
            ],
        );

        self::assertEquals(EnumNode::fromArray($enumNode->toArray()), $enumNode);
    }
}
