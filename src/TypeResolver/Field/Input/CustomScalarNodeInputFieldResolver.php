<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class CustomScalarNodeInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgNode $child, Ast $ast): bool
    {
        return $child->reference instanceof ObjectReference && $ast->getNodeByClassName($child->reference->className) instanceof CustomScalarNode;
    }

    public function resolve(FieldNode|ArgNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed
    {
        return $args[$child->name];
    }
}
