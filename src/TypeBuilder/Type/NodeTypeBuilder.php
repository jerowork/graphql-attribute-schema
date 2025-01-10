<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\NodeType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;

/**
 * @template T of NodeType
 */
interface NodeTypeBuilder
{
    /**
     * @param T $type
     */
    public function supports(NodeType $type): bool;

    /**
     * @param T $type
     *
     * @throws BuildException
     */
    public function build(NodeType $type, TypeBuilder $typeBuilder, Ast $ast): Type;
}
