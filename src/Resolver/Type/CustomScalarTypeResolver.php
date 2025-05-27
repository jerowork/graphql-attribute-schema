<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\ScalarType;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use LogicException;
use Override;

/**
 * @internal
 */
final class CustomScalarTypeResolver implements TypeResolver
{
    use TypeResolverSelectorAwareTrait;
    use GetNodeFromReferenceTrait;

    public function __construct(
        private readonly AstContainer $astContainer,
    ) {}

    #[Override]
    public function supports(TypeReference $reference): bool
    {
        return $reference instanceof ObjectTypeReference && $this->astContainer->getAst()->getNodeByClassName($reference->className) instanceof ScalarNode;
    }

    #[Override]
    public function createType(TypeReference $reference): ScalarType
    {
        $node = $this->getNodeFromReference($reference, $this->astContainer->getAst(), ScalarNode::class);

        /** @var class-string<ScalarType> $scalarType */
        $scalarType = $node->className;

        return new $scalarType([
            'name' => $node->name,
            'description' => $node->description,
        ]);
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
            throw new LogicException(sprintf('CustomScalarType: Node must be either FieldNode or ArgNode, %s given', $node::class));
        }

        return $args[$node->name] ?? null;
    }
}
