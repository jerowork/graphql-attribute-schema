<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node\Child;

use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class FieldNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $fieldNode = new FieldNode(
            ObjectTypeReference::create(stdClass::class),
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
                new AutowireNode(
                    stdClass::class,
                    'service',
                ),
                new EdgeArgsNode('property'),
            ],
            FieldNodeType::Method,
            'method',
            null,
            'deprecated',
        );

        self::assertEquals(FieldNode::fromArray($fieldNode->toArray()), $fieldNode);
    }
}
