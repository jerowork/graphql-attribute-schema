<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output;

use BackedEnum;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ListableReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;

final readonly class EnumNodeOutputFieldResolver implements OutputFieldResolver
{
    public function supports(FieldNode $field, Ast $ast): bool
    {
        return $field->reference instanceof ObjectReference && $ast->getNodeByClassName($field->reference->className) instanceof EnumNode;
    }

    public function resolve(FieldNode $field, callable $fieldCallback, Ast $ast): mixed
    {
        if ($field->reference instanceof ListableReference && $field->reference->isList()) {
            /** @var list<BackedEnum> $enums */
            $enums = $fieldCallback();

            return array_map(fn($enum) => $enum->value, $enums);
        }

        /** @var BackedEnum $enum */
        $enum = $fieldCallback();

        return $enum->value;
    }
}
