<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\TypeResolver\ResolveException;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class CustomScalarNodeInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgNode|EdgeArgsNode $child, Ast $ast): bool
    {
        return !$child instanceof EdgeArgsNode && $child->reference instanceof ObjectTypeReference && $ast->getNodeByClassName($child->reference->className) instanceof CustomScalarNode;
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
