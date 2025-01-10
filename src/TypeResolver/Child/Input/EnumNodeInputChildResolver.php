<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\TypeResolver\ResolveException;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class EnumNodeInputChildResolver implements InputChildResolver
{
    public function supports(FieldNode|ArgNode $child, Ast $ast): bool
    {
        return $child->type instanceof ObjectNodeType && $ast->getNodeByClassName($child->type->className) instanceof EnumNode;
    }

    /**
     * @throws ResolveException
     */
    public function resolve(FieldNode|ArgNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed
    {
        if (!$child->type instanceof ObjectNodeType) {
            throw ResolveException::logicError('Child type must be an object type');
        }

        /** @var EnumNode $node */
        $node = $ast->getNodeByClassName($child->type->className);

        $className = $node->getClassName();

        if ($child->type->isList()) {
            /** @var list<string> $value */
            $value = $args[$child->name];

            return array_map(fn($item) => $className::from($item), $value);
        }

        /** @var string $value */
        $value = $args[$child->name];

        return $className::from($value);
    }
}
