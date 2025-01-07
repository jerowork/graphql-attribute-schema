<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\Node;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeNode;
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
                    Type::createObject(stdClass::class),
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
