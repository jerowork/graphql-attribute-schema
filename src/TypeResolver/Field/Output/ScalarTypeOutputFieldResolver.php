<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;

final readonly class ScalarTypeOutputFieldResolver implements OutputFieldResolver
{
    public function supports(FieldNode $field, Ast $ast): bool
    {
        return $field->reference instanceof ScalarTypeReference;
    }

    public function resolve(FieldNode $field, callable $fieldCallback, Ast $ast): mixed
    {
        return $fieldCallback();
    }
}
