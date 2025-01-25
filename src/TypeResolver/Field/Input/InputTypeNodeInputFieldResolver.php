<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\TypeResolver\ResolveException;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

/**
 * @internal
 */
final readonly class InputTypeNodeInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgumentNode $child, Ast $ast): bool
    {
        return ($child instanceof FieldNode || $child instanceof ArgNode)
            && $child->reference instanceof ObjectTypeReference
            && $ast->getNodeByClassName($child->reference->className) instanceof InputTypeNode;
    }

    /**
     * @throws ResolveException
     */
    public function resolve(FieldNode|ArgumentNode $child, array $args, Ast $ast, RootTypeResolver $rootTypeResolver): mixed
    {
        if (!$child instanceof FieldNode && !$child instanceof ArgNode) {
            throw ResolveException::logicError(sprintf('Invalid child %s', $child::class));
        }

        if (!$child->reference instanceof ObjectTypeReference) {
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
