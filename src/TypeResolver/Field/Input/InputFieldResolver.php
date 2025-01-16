<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

interface InputFieldResolver
{
    public function supports(ArgNode|FieldNode|EdgeArgsNode $child, Ast $ast): bool;

    /**
     * @param array<string, mixed> $args
     */
    public function resolve(ArgNode|FieldNode|EdgeArgsNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed;
}
