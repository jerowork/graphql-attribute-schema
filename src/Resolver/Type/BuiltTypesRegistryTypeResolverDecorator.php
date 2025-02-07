<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\AliasedNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use LogicException;
use Override;

/**
 * @internal
 */
final readonly class BuiltTypesRegistryTypeResolverDecorator implements TypeResolver
{
    public function __construct(
        private AstContainer $astContainer,
        private TypeResolver $typeResolver,
        private BuiltTypesRegistry $builtTypesRegistry,
    ) {}

    #[Override]
    public function setTypeResolverSelector(TypeResolverSelector $typeResolverSelector): void
    {
        $this->typeResolver->setTypeResolverSelector($typeResolverSelector);
    }

    #[Override]
    public function getTypeResolverSelector(): TypeResolverSelector
    {
        return $this->typeResolver->getTypeResolverSelector();
    }

    #[Override]
    public function supports(TypeReference $reference): bool
    {
        return $this->typeResolver->supports($reference);
    }

    #[Override]
    public function createType(TypeReference $reference): Type
    {
        if (!$reference instanceof ObjectTypeReference) {
            return $this->typeResolver->createType($reference);
        }

        $node = $this->astContainer->getAst()->getNodeByClassName($reference->className);

        if ($node === null) {
            throw new LogicException(sprintf('No node found for class %s', $reference->className));
        }

        $className = $node instanceof AliasedNode && $node->getAlias() !== null ? $node->getAlias() : $node->getClassName();

        if ($this->builtTypesRegistry->hasType($className)) {
            return $this->builtTypesRegistry->getType($className);
        }

        $type = $this->typeResolver->createType($reference);

        $this->builtTypesRegistry->addType($className, $type);

        return $type;
    }

    #[Override]
    public function resolve(TypeReference $reference, Closure $callback): mixed
    {
        return $this->typeResolver->resolve($reference, $callback);
    }

    #[Override]
    public function abstract(FieldNode|ArgumentNode $node, array $args): mixed
    {
        return $this->typeResolver->abstract($node, $args);
    }
}
