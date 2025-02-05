<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Type\Object;

use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ExecutingObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\BuildArgsTrait;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class BuildArgsTraitTest extends TestCase
{
    #[Test]
    public function itShouldBuildArgs(): void
    {
        $trait = new class {
            use BuildArgsTrait;
        };

        /** @var iterable<TypeBuilder<TypeReference>> $nodeTypeBuilders */
        $nodeTypeBuilders = [
            new ScalarTypeBuilder(),
            new ExecutingObjectTypeBuilder(new BuiltTypesRegistry(), []),
        ];

        $args = $trait->buildArgs(
            new FieldNode(
                ScalarTypeReference::create('string'),
                'name',
                null,
                [
                    new ArgNode(
                        ScalarTypeReference::create('string'),
                        'arg1',
                        'Arg 1 description',
                        'arg1',
                    ),
                    new ArgNode(
                        ScalarTypeReference::create('bool')->setNullableValue(),
                        'arg2',
                        'Arg 2 description',
                        'arg2',
                    ),
                ],
                FieldNodeType::Property,
                null,
                'name',
                null,
            ),
            new ExecutingTypeBuilder($nodeTypeBuilders),
            new Ast(),
        );

        self::assertEquals([
            [
                'name' => 'arg1',
                'type' => WebonyxType::nonNull(WebonyxType::string()),
                'description' => 'Arg 1 description',
            ],
            [
                'name' => 'arg2',
                'type' => WebonyxType::boolean(),
                'description' => 'Arg 2 description',
            ],
        ], $args);
    }
}
