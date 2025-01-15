<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

/**
 * @internal
 */
final class CursorNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $cursorNode = new CursorNode(
            ObjectReference::create(stdClass::class),
            FieldNodeType::Property,
            null,
            'name',
        );

        self::assertEquals(CursorNode::fromArray($cursorNode->toArray()), $cursorNode);
    }
}
