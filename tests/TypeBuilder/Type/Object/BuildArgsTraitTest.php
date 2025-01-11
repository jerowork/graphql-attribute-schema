<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\NodeType\Object;

use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ExecutingObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\BuildArgsTrait;
use PHPUnit\Framework\Attributes\Test;

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

        /** @var iterable<TypeBuilder<Reference>> $nodeTypeBuilders */
        $nodeTypeBuilders = [
            new ScalarTypeBuilder(),
            new ExecutingObjectTypeBuilder(new BuiltTypesRegistry(), []),
        ];

        $args = $trait->buildArgs(
            new FieldNode(
                ScalarReference::create('string'),
                'name',
                null,
                [
                    new ArgNode(
                        ScalarReference::create('string'),
                        'arg1',
                        'Arg 1 description',
                        'arg1',
                    ),
                    new ArgNode(
                        ScalarReference::create('bool')->setNullableValue(),
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
