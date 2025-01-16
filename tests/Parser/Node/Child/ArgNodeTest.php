<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ScalarTypeReference;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use PHPUnit\Framework\Attributes\Test;

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
