<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestScalarType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ScalarNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $scalarNode = new ScalarNode(
            TestScalarType::class,
            'name',
            'description',
            DateTimeImmutable::class,
        );

        self::assertEquals(ScalarNode::fromArray($scalarNode->toArray()), $scalarNode);
    }
}
