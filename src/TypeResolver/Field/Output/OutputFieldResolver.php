<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;

/**
 * @internal
 */
interface OutputFieldResolver
{
    public function supports(FieldNode $field, Ast $ast): bool;

    public function resolve(FieldNode $field, callable $fieldCallback, Ast $ast): mixed;
}
