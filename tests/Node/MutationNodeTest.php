<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node;

use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader\TestTypeLoader;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

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
            TestTypeLoader::class,
        );

        self::assertEquals(MutationNode::fromArray($mutationNode->toArray()), $mutationNode);
    }
}
