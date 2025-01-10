<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\NodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Override;

/**
 * @implements NodeTypeBuilder<ScalarNodeType>
 */
final readonly class ScalarNodeTypeBuilder implements NodeTypeBuilder
{
    #[Override]
    public function supports(NodeType $type): bool
    {
        return $type instanceof ScalarNodeType;
    }

    #[Override]
    public function build(NodeType $type, TypeBuilder $typeBuilder, Ast $ast): Type
    {
        return match ($type->value) {
            'string' => Type::string(),
            'int' => Type::int(),
            'float' => Type::float(),
            'bool' => Type::boolean(),
            default => throw BuildException::logicError(sprintf('Invalid type: %s', $type->value)),
        };
    }
}
