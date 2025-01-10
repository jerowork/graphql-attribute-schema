<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class ScalarTypeInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgNode $child, Ast $ast): bool
    {
        return $child->type instanceof ScalarNodeType;
    }

    public function resolve(FieldNode|ArgNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed
    {
        return $args[$child->name];
    }
}
