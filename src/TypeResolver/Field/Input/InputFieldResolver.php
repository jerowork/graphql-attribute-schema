<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

/**
 * @internal
 */
interface InputFieldResolver
{
    public function supports(FieldNode|ArgumentNode $child, Ast $ast): bool;

    /**
     * @param array<string, mixed> $args
     */
    public function resolve(FieldNode|ArgumentNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed;
}
