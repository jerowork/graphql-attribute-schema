<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\TypeResolver\ResolveException;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

/**
 * @internal
 */
final readonly class EnumNodeInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgumentNode $child, Ast $ast): bool
    {
        return ($child instanceof FieldNode || $child instanceof ArgNode)
            && $child->reference instanceof ObjectTypeReference
            && $ast->getNodeByClassName($child->reference->className) instanceof EnumNode;
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
