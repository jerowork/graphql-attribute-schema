<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

/**
 * @internal
 */
final readonly class EdgeArgsInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgumentNode $child, Ast $ast): bool
    {
        return $child instanceof EdgeArgsNode;
    }

    /**
     * @param array{
     *     first?: int,
     *     after?: string,
     *     last?: int,
     *     before?: string
     * } $args
     */
    public function resolve(FieldNode|ArgumentNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed
    {
        return new EdgeArgs(
            $args['first'] ?? null,
            $args['after'] ?? null,
            $args['last'] ?? null,
            $args['before'] ?? null,
        );
    }
}
