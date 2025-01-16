<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Method;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ScalarTypeReference;
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
                    ScalarTypeReference::create('int'),
                    'name',
                    'a description',
                    'aPropertyName',
                ),
                new ArgNode(
                    ScalarTypeReference::create('string'),
                    'name 2',
                    'b description',
                    'bPropertyName',
                ),
                new EdgeArgsNode('property'),
            ],
            ObjectTypeReference::create(TestType::class),
            'method',
            'deprecated',
        );

        self::assertEquals(MutationNode::fromArray($mutationNode->toArray()), $mutationNode);
    }
}
