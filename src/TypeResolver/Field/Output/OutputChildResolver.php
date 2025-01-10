<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;

interface OutputChildResolver
{
    public function supports(FieldNode $field, Ast $ast): bool;

    public function resolve(FieldNode $field, callable $fieldCallback, Ast $ast): mixed;
}
