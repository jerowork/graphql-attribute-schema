<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Override;

/**
 * @implements NodeTypeBuilder<ScalarReference>
 */
final readonly class ScalarNodeTypeBuilder implements NodeTypeBuilder
{
    #[Override]
    public function supports(Reference $reference): bool
    {
        return $reference instanceof ScalarReference;
    }

    #[Override]
    public function build(Reference $reference, TypeBuilder $typeBuilder, Ast $ast): Type
    {
        return match ($reference->value) {
            'string' => Type::string(),
            'int' => Type::int(),
            'float' => Type::float(),
            'bool' => Type::boolean(),
            default => throw BuildException::logicError(sprintf('Invalid type: %s', $reference->value)),
        };
    }
}
