<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node\Child;

use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ArgNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $argNode = new ArgNode(
            ScalarTypeReference::create('int'),
            'name',
            'a description',
            'aPropertyName',
        );

        self::assertEquals(ArgNode::fromArray($argNode->toArray()), $argNode);
    }
}
