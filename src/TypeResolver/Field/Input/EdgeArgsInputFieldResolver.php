<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class EdgeArgsInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgNode|EdgeArgsNode $child, Ast $ast): bool
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
    public function resolve(FieldNode|ArgNode|EdgeArgsNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed
    {
        return new EdgeArgs(
            $args['first'] ?? null,
            $args['after'] ?? null,
            $args['last'] ?? null,
            $args['before'] ?? null,
        );
    }
}
