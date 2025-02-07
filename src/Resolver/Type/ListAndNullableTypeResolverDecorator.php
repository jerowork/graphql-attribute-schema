<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ListableTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Override;

/**
 * @internal
 */
final readonly class ListAndNullableTypeResolverDecorator implements TypeResolver
{
    public function __construct(
        private TypeResolver $typeResolver,
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
        $type = $this->typeResolver->createType($reference);

        if (!$reference->isValueNullable()) {
            /** @var Type&NullableType $nullableType */
            $nullableType = $type;
            $type = Type::nonNull($nullableType);
        }

        if ($reference instanceof ListableTypeReference && $reference->isList()) {
            $type = Type::listOf($type);

            if (!$reference->isListNullable()) {
                $type = Type::nonNull($type);
            }
        }

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
