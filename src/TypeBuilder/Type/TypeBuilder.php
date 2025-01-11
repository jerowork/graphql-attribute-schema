<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;

/**
 * @template T of Reference
 */
interface TypeBuilder
{
    /**
     * @param T $reference
     */
    public function supports(Reference $reference): bool;

    /**
     * @param T $reference
     *
     * @throws BuildException
     */
    public function build(Reference $reference, ExecutingTypeBuilder $typeBuilder, Ast $ast): Type;
}
