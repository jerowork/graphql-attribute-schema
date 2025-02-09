<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;

/**
 * @internal
 */
interface TypeResolver extends TypeResolverSelectorAware
{
    public function supports(TypeReference $reference): bool;

    public function createType(TypeReference $reference): Type;

    public function resolve(TypeReference $reference, Closure $callback): mixed;

    /**
     * @param array<string, mixed> $args
     */
    public function abstract(ArgumentNode|FieldNode $node, array $args): mixed;
}
