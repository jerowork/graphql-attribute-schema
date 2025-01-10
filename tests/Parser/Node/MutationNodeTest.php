<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
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
                    Type::createScalar('int'),
                    'name',
                    'a description',
                    'aPropertyName',
                ),
                new ArgNode(
                    Type::createScalar('string'),
                    'name 2',
                    'b description',
                    'bPropertyName',
                ),
            ],
            Type::createObject(TestType::class),
            'method',
            'deprecated',
        );

        self::assertEquals(MutationNode::fromArray($mutationNode->toArray()), $mutationNode);
    }
}
