<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder\Object;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNodeType;
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
                null,
                'string',
                'name',
                null,
                true,
                [
                    new ArgNode(
                        null,
                        'string',
                        'arg1',
                        'Arg 1 description',
                        true,
                        'arg1',
                    ),
                    new ArgNode(
                        null,
                        'bool',
                        'arg2',
                        'Arg 2 description',
                        false,
                        'arg2',
                    ),
                ],
                FieldNodeType::Property,
                null,
                'name',
            ),
            new TypeBuilder([]),
            new Ast(),
        );

        self::assertEquals([
            [
                'name' => 'arg1',
                'type' => Type::nonNull(Type::string()),
                'description' => 'Arg 1 description',
            ],
            [
                'name' => 'arg2',
                'type' => Type::boolean(),
                'description' => 'Arg 2 description',
            ],
        ], $args);
    }
}
