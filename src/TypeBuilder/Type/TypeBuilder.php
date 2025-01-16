<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;

/**
 * @template T of TypeReference
 */
interface TypeBuilder
{
    /**
     * @param T $reference
     */
    public function supports(TypeReference $reference): bool;

    /**
     * @param T $reference
     *
     * @throws BuildException
     */
    public function build(TypeReference $reference, ExecutingTypeBuilder $typeBuilder, Ast $ast): Type;
}
