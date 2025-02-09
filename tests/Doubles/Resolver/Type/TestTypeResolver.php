<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelectorAwareTrait;

final class TestTypeResolver implements TypeResolver
{
    use TypeResolverSelectorAwareTrait;

    public bool $createTypeIsCalled = false;
    public Type $createdType;

    public function supports(TypeReference $reference): bool
    {
        return true;
    }

    public function createType(TypeReference $reference): Type
    {
        $this->createTypeIsCalled = true;

        return $this->createdType;
    }

    public function resolve(TypeReference $reference, Closure $callback): mixed
    {
        return '';
    }

    public function abstract(ArgumentNode|FieldNode $node, array $args): mixed
    {
        return '';
    }
}
