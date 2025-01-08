<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class CustomScalarNodeInputChildResolver implements InputChildResolver
{
    public function supports(FieldNode|ArgNode $child, Ast $ast): bool
    {
        return $ast->getNodeByClassName($child->type->value) instanceof CustomScalarNode;
    }

    public function resolve(FieldNode|ArgNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed
    {
        return $args[$child->name];
    }
}
