<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use BackedEnum;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;

final readonly class FieldResolver
{
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
        if ($field->type->isScalar()) {
            return $fieldCallback();
        }

        $node = $ast->getNodeByClassName($field->type->id);

        if ($node instanceof EnumNode) {
            /** @var BackedEnum $enum */
            $enum = $fieldCallback();

            return $enum->value;
        }

        return $fieldCallback();
    }
}
