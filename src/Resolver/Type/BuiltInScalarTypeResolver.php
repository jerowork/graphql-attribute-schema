<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use LogicException;
use Override;

/**
 * @internal
 */
final class BuiltInScalarTypeResolver implements TypeResolver
{
    use TypeResolverSelectorAwareTrait;

    #[Override]
    public function supports(TypeReference $reference): bool
    {
        return $reference instanceof ScalarTypeReference;
    }

    #[Override]
    public function createType(TypeReference $reference): Type
    {
        if (!$reference instanceof ScalarTypeReference) {
            throw new LogicException(sprintf('BuiltInScalarType: Reference must be ScalarTypeReference, %s given', $reference::class));
        }

        return match ($reference->value) {
            'string' => Type::string(),
            'int' => Type::int(),
            'float' => Type::float(),
            'bool' => Type::boolean(),
            default => throw new LogicException(sprintf('BuiltInScalarType: Invalid reference type %s', $reference->value)),
        };
    }

    #[Override]
    public function resolve(TypeReference $reference, Closure $callback): mixed
    {
        return $callback();
    }

    #[Override]
    public function abstract(ArgumentNode|FieldNode $node, array $args): mixed
    {
        if (!$node instanceof FieldNode && !$node instanceof ArgNode) {
            throw new LogicException(sprintf('BuiltInScalarType: Node must be either FieldNode or ArgNode, %s given', $node::class));
        }

        return $args[$node->name];
    }
}
