<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use GraphQL\Type\Definition\Type;

/**
 * @template T of Node
 */
interface ObjectTypeBuilder
{
    public function supports(Node $node): bool;

    /**
     * @param T $node
     *
     * @throws BuildException
     */
    public function build(Node $node, ExecutingTypeBuilder $typeBuilder, Ast $ast): Type;
}
