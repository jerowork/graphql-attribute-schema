<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Output\OutputChildResolver;

final readonly class FieldResolver
{
    /**
     * @param iterable<OutputChildResolver> $outputResolvers
     */
    public function __construct(
        private iterable $outputResolvers,
    ) {}

    public function resolve(FieldNode $fieldNode, Ast $ast): callable
    {
        return function (object $object, array $args) use ($fieldNode, $ast) {
            if ($fieldNode->fieldType === FieldNodeType::Property) {
                return $this->resolveChild(
                    $fieldNode,
                    fn() => $object->{$fieldNode->propertyName},
                    $ast,
                );
            }

            return $this->resolveChild(
                $fieldNode,
                fn() => $object->{$fieldNode->methodName}(...array_map(
                    fn($argNode) => $args[$argNode->name],
                    $fieldNode->argNodes,
                )),
                $ast,
            );
        };
    }

    /**
     * @throws ResolveException
     */
    private function resolveChild(FieldNode $field, callable $fieldCallback, Ast $ast): mixed
    {
        foreach ($this->outputResolvers as $outputResolver) {
            if (!$outputResolver->supports($field, $ast)) {
                continue;
            }

            return $outputResolver->resolve($field, $fieldCallback, $ast);
        }

        // Fallback
        return $fieldCallback();
    }
}
