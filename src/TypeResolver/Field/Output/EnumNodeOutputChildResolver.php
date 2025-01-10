<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output;

use BackedEnum;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ListableNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;

final readonly class EnumNodeOutputChildResolver implements OutputChildResolver
{
    public function supports(FieldNode $field, Ast $ast): bool
    {
        return $field->type instanceof ObjectNodeType && $ast->getNodeByClassName($field->type->className) instanceof EnumNode;
    }

    public function resolve(FieldNode $field, callable $fieldCallback, Ast $ast): mixed
    {
        if ($field->type instanceof ListableNodeType && $field->type->isList()) {
            /** @var list<BackedEnum> $enums */
            $enums = $fieldCallback();

            return array_map(fn($enum) => $enum->value, $enums);
        }

        /** @var BackedEnum $enum */
        $enum = $fieldCallback();

        return $enum->value;
    }
}
