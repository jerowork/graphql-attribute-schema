<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Object;

use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\BuildArgsTrait;
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

        $args = $trait->buildArgs(
            new FieldNode(
                Type::createScalar('string'),
                'name',
                null,
                [
                    new ArgNode(
                        Type::createScalar('string'),
                        'arg1',
                        'Arg 1 description',
                        'arg1',
                    ),
                    new ArgNode(
                        Type::createScalar('bool')->setNullableValue(),
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
            new TypeBuilder([]),
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
