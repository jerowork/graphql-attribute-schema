<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\TypeResolver\ResolveException;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class ScalarTypeInputFieldResolver implements InputFieldResolver
{
    public function supports(FieldNode|ArgNode|EdgeArgsNode $child, Ast $ast): bool
    {
        return ($child instanceof FieldNode || $child instanceof ArgNode) && $child->reference instanceof ScalarReference;
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
