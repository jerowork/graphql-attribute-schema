<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Method;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\QueryNode;
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
                    ScalarNodeType::create('int'),
                    'name',
                    'a description',
                    'aPropertyName',
                ),
                new ArgNode(
                    ScalarNodeType::create('string'),
                    'name 2',
                    'b description',
                    'bPropertyName',
                ),
            ],
            ObjectNodeType::create(TestType::class),
            'method',
            'deprecated',
        );

        self::assertEquals(QueryNode::fromArray($queryNode->toArray()), $queryNode);
    }
}
