<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\UnionType;
use GraphQL\Type\Definition\WrappingType;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\UnionTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use LogicException;
use Override;

/**
 * @internal
 */
final class UnionTypeResolver implements TypeResolver
{
    use TypeResolverSelectorAwareTrait;

    public function __construct(
        private BuiltTypesRegistry $builtTypesRegistry,
    ) {}

    #[Override]
    public function supports(TypeReference $reference): bool
    {
        return $reference instanceof UnionTypeReference;
    }

    #[Override]
    public function createType(TypeReference $reference): UnionType
    {
        if (!$reference instanceof UnionTypeReference) {
            throw new LogicException('Reference is not a UnionTypeReference');
        }

        return new UnionType([
            'name' => $reference->name,
            'types' => array_map(
                function (string $className) {
                    $reference = ObjectTypeReference::create($className);
                    $type = $this->getTypeResolverSelector()->getResolver($reference)->createType($reference);

                    return $type instanceof WrappingType ? $type->getWrappedType() : $type;
                },
                $reference->classNames,
            ),
            'resolveType' => fn(object $objectValue) => $this->builtTypesRegistry->getType($objectValue::class),
        ]);
    }

    #[Override]
    public function resolve(TypeReference $reference, Closure $callback): mixed
    {
        throw new LogicException('UnionType does not need to resolve');
    }

    #[Override]
    public function abstract(ArgumentNode|FieldNode $node, array $args): mixed
    {
        throw new LogicException('UnionType does not need to abstract');
    }
}
