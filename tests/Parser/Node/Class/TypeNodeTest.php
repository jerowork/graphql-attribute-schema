<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node\Class;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
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
                    ],
                    FieldNodeType::Method,
                    'method',
                    null,
                    'deprecated',
                ),
            ],
        );

        self::assertEquals(TypeNode::fromArray($typeNode->toArray()), $typeNode);
    }
}
