<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Method;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\MutationNode;
use PHPUnit\Framework\Attributes\Test;

/**
 * @internal
 */
final class MutationNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $mutationNode = new MutationNode(
            TestMutation::class,
            'name',
            'description',
            [
                new ArgNode(
                    ScalarReference::create('int'),
                    'name',
                    'a description',
                    'aPropertyName',
                ),
                new ArgNode(
                    ScalarReference::create('string'),
                    'name 2',
                    'b description',
                    'bPropertyName',
                ),
            ],
            ObjectReference::create(TestType::class),
            'method',
            'deprecated',
        );

        self::assertEquals(MutationNode::fromArray($mutationNode->toArray()), $mutationNode);
    }
}
