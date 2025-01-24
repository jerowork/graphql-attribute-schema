<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Override;

/**
 * @implements TypeBuilder<ScalarTypeReference>
 *
 * @internal
 */
final readonly class ScalarTypeBuilder implements TypeBuilder
{
    #[Override]
    public function supports(TypeReference $reference): bool
    {
        return $reference instanceof ScalarTypeReference;
    }

    #[Override]
    public function build(TypeReference $reference, ExecutingTypeBuilder $typeBuilder, Ast $ast): Type
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
