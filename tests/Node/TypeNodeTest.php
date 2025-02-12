<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node;

use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\CursorNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class TypeNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $typeNode = new TypeNode(
            TestType::class,
            'name',
            'description',
            [
                new FieldNode(
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
                    ],
                    FieldNodeType::Method,
                    'method',
                    null,
                    'deprecated',
                ),
            ],
            new CursorNode(
                ScalarTypeReference::create('string'),
                FieldNodeType::Property,
                null,
                'property',
            ),
            [],
        );

        self::assertEquals(TypeNode::fromArray($typeNode->toArray()), $typeNode);
    }
}
