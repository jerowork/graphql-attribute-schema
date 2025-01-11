<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\TypeResolver\ResolveException;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class InputTypeNodeInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgNode $child, Ast $ast): bool
    {
        return $child->reference instanceof ObjectReference && $ast->getNodeByClassName($child->reference->className) instanceof InputTypeNode;
    }

    /**
     * @throws ResolveException
     */
    public function resolve(FieldNode|ArgNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed
    {
        if (!$child->reference instanceof ObjectReference) {
            throw ResolveException::logicError('Node type must be an object type');
        }

        /** @var InputTypeNode $node */
        $node = $ast->getNodeByClassName($child->reference->className);

        $className = $child->reference->className;

        if ($child->reference->isList()) {
            /** @var list<array<string, mixed>> $childArgs */
            $childArgs = $args[$child->name];

            return array_map(
                fn($item) => new $className(...array_map(
                    fn($fieldNode) => $rootTypeResolver->resolveField($fieldNode, $item, $ast),
                    $node->fieldNodes,
                )),
                $childArgs,
            );
        }

        /** @var array<string, mixed> $childArgs */
        $childArgs = $args[$child->name];

        return new $className(...array_map(
            fn($fieldNode) => $rootTypeResolver->resolveField($fieldNode, $childArgs, $ast),
            $node->fieldNodes,
        ));
    }
}
