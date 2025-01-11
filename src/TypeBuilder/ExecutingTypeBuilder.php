<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ListableReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;

final readonly class ExecutingTypeBuilder
{
    /**
     * @param iterable<TypeBuilder<Reference>> $typeBuilders
     */
    public function __construct(
        private iterable $typeBuilders,
    ) {}

    /**
     * @throws BuildException
     */
    public function build(Reference $reference, Ast $ast): WebonyxType
    {
        $builtType = null;

        foreach ($this->typeBuilders as $typeBuilder) {
            if (!$typeBuilder->supports($reference)) {
                continue;
            }

            $builtType = $typeBuilder->build($reference, $this, $ast);
        }

        if ($builtType === null) {
            throw BuildException::logicError('Cannot build type');
        }

        if (!$reference->isValueNullable()) {
            $builtType = WebonyxType::nonNull($builtType); // @phpstan-ignore-line
        }

        if ($reference instanceof ListableReference && $reference->isList()) {
            $builtType = WebonyxType::listOf($builtType);

            if (!$reference->isListNullable()) {
                $builtType = WebonyxType::nonNull($builtType);
            }
        }

        return $builtType;
    }
}
