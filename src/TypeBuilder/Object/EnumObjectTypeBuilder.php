<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Object;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Override;

/**
 * @implements ObjectTypeBuilder<EnumNode>
 */
final readonly class EnumObjectTypeBuilder implements ObjectTypeBuilder
{
    #[Override]
    public function supports(Node $node): bool
    {
        return $node instanceof EnumNode;
    }

    #[Override]
    public function build(Node $node, TypeBuilder $typeBuilder, Ast $ast): Type
    {
        return new EnumType([
            'name' => $node->name,
            'description' => $node->description,
            'values' => $node->cases,
        ]);
    }
}
