<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\InterfaceType;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\InterfaceTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use LogicException;
use Override;

/**
 * @internal
 */
final class InterfaceTypeResolver implements TypeResolver
{
    use TypeResolverSelectorAwareTrait;
    use GetNodeFromReferenceTrait;

    public function __construct(
        private readonly AstContainer $astContainer,
        private readonly BuiltTypesRegistry $builtTypesRegistry,
        private readonly FieldResolver $fieldResolver,
    ) {}

    #[Override]
    public function supports(TypeReference $reference): bool
    {
        if (!$reference instanceof ObjectTypeReference) {
            return false;
        }

        $node = $this->astContainer->getAst()->getNodeByClassName($reference->className);

        return $node instanceof InterfaceTypeNode;
    }

    #[Override]
    public function createType(TypeReference $reference): InterfaceType
    {
        $node = $this->getNodeFromReference($reference, $this->astContainer->getAst(), InterfaceTypeNode::class);

        return new InterfaceType([
            'name' => $node->name,
            'description' => $node->description,
            'fields' => $this->fieldResolver->getFields($node->fieldNodes, $this->getTypeResolverSelector()),
            'resolveType' => fn(object $objectValue) => $this->builtTypesRegistry->getType($objectValue::class),
        ]);
    }

    #[Override]
    public function resolve(TypeReference $reference, Closure $callback): mixed
    {
        throw new LogicException('InterfaceType does not need to resolve');
    }

    #[Override]
    public function abstract(ArgumentNode|FieldNode $node, array $args): mixed
    {
        throw new LogicException('InterfaceType does not need to abstract');
    }
}
