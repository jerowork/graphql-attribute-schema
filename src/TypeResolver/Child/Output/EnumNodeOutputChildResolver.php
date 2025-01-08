<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Output;

use BackedEnum;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;

final readonly class EnumNodeOutputChildResolver implements OutputChildResolver
{
    public function supports(FieldNode $field, Ast $ast): bool
    {
        return $ast->getNodeByClassName($field->type->value) instanceof EnumNode;
    }

    public function resolve(FieldNode $field, callable $fieldCallback, Ast $ast): mixed
    {
        if ($field->type->isList()) {
            /** @var list<BackedEnum> $enums */
            $enums = $fieldCallback();

            return array_map(fn($enum) => $enum->value, $enums);
        }

        /** @var BackedEnum $enum */
        $enum = $fieldCallback();

        return $enum->value;
    }
}
