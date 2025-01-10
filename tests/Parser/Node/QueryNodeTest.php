<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
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

        self::assertEquals(QueryNode::fromArray($queryNode->toArray()), $queryNode);
    }
}
