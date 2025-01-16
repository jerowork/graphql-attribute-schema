<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node\Child;

use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
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
            ObjectTypeReference::create(stdClass::class),
            FieldNodeType::Property,
            null,
            'name',
        );

        self::assertEquals(CursorNode::fromArray($cursorNode->toArray()), $cursorNode);
    }
}
