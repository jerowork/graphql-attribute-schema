<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node;

use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Node\QueryNode;
use PHPUnit\Framework\Attributes\Test;

/**
 * @internal
 */
final class QueryNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $queryNode = new QueryNode(
            TestQuery::class,
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

        self::assertEquals(QueryNode::fromArray($queryNode->toArray()), $queryNode);
    }
}
