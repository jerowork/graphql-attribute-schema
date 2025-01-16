<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Node\Class;

use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestInputType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class InputTypeNodeTest extends TestCase
{
    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $inputTypeNode = new InputTypeNode(
            TestInputType::class,
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
        );

        self::assertEquals(InputTypeNode::fromArray($inputTypeNode->toArray()), $inputTypeNode);
    }
}
