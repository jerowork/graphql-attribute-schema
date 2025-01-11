<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\TypeResolver\ResolveException;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class EnumNodeInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgNode $child, Ast $ast): bool
    {
        return $child->reference instanceof ObjectReference && $ast->getNodeByClassName($child->reference->className) instanceof EnumNode;
    }

    /**
     * @throws ResolveException
     */
    public function resolve(FieldNode|ArgNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed
    {
        if (!$child->reference instanceof ObjectReference) {
            throw ResolveException::logicError('Node type must be an object type');
        }

        /** @var EnumNode $node */
        $node = $ast->getNodeByClassName($child->reference->className);

        $className = $node->getClassName();

        if ($child->reference->isList()) {
            /** @var list<string> $value */
            $value = $args[$child->name];

            return array_map(fn($item) => $className::from($item), $value);
        }

        /** @var string $value */
        $value = $args[$child->name];

        return $className::from($value);
    }
}
