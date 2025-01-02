<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Object;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;

/**
 * @template T of Node
 */
interface ObjectTypeBuilder
{
    public function supports(Node $node): bool;

    /**
     * @param T $node
     */
    public function build(Node $node, TypeBuilder $typeBuilder, Ast $ast): Type;
}
