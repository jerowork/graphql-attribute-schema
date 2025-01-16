<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\TypeResolver\ResolveException;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class CustomScalarNodeInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgNode|EdgeArgsNode $child, Ast $ast): bool
    {
        return !$child instanceof EdgeArgsNode && $child->reference instanceof ObjectReference && $ast->getNodeByClassName($child->reference->className) instanceof CustomScalarNode;
    }

    /**
     * @throws ResolveException
     */
    public function resolve(FieldNode|ArgNode|EdgeArgsNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed
    {
        if ($child instanceof EdgeArgsNode) {
            throw ResolveException::logicError(sprintf('Invalid child %s', $child::class));
        }

        return $args[$child->name];
    }
}
