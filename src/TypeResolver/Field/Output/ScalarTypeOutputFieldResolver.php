<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;

final readonly class ScalarTypeOutputFieldResolver implements OutputFieldResolver
{
    public function supports(FieldNode $field, Ast $ast): bool
    {
        return $field->type instanceof ScalarNodeType;
    }

    public function resolve(FieldNode $field, callable $fieldCallback, Ast $ast): mixed
    {
        return $fieldCallback();
    }
}
