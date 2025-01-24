<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Override;

/**
 * @implements ObjectTypeBuilder<EnumNode>
 *
 * @internal
 */
final readonly class EnumObjectTypeBuilder implements ObjectTypeBuilder
{
    #[Override]
    public function supports(Node $node): bool
    {
        return $node instanceof EnumNode;
    }

    #[Override]
    public function build(Node $node, ExecutingTypeBuilder $typeBuilder, Ast $ast): Type
    {
        $values = [];
        foreach ($node->cases as $case) {
            $value = [
                'value' => $case->value,
                'description' => $case->description,
            ];

            if ($case->deprecationReason !== null) {
                $value['deprecationReason'] = $case->deprecationReason;
            }

            $values[$case->value] = $value;
        }

        return new EnumType([
            'name' => $node->name,
            'description' => $node->description,
            'values' => $values,
        ]);
    }
}
