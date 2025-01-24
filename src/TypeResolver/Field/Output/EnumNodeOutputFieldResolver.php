<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output;

use BackedEnum;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ListableTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;

/**
 * @internal
 */
final readonly class EnumNodeOutputFieldResolver implements OutputFieldResolver
{
    public function supports(FieldNode $field, Ast $ast): bool
    {
        return $field->reference instanceof ObjectTypeReference && $ast->getNodeByClassName($field->reference->className) instanceof EnumNode;
    }

    public function resolve(FieldNode $field, callable $fieldCallback, Ast $ast): mixed
    {
        if ($field->reference instanceof ListableTypeReference && $field->reference->isList()) {
            /** @var list<BackedEnum> $enums */
            $enums = $fieldCallback();

            return array_map(fn($enum) => $enum->value, $enums);
        }

        /** @var BackedEnum $enum */
        $enum = $fieldCallback();

        return $enum->value;
    }
}
