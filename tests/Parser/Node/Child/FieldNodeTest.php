<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
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
            ObjectReference::create(stdClass::class),
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
                new AutowireNode(
                    stdClass::class,
                    'service',
                ),
            ],
            FieldNodeType::Method,
            'method',
            null,
            'deprecated',
        );

        self::assertEquals(FieldNode::fromArray($fieldNode->toArray()), $fieldNode);
    }
}
