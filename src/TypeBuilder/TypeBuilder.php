<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ListableReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\NodeTypeBuilder;

final readonly class TypeBuilder
{
    /**
     * @param iterable<NodeTypeBuilder<Reference>> $nodeTypeBuilders
     */
    public function __construct(
        private iterable $nodeTypeBuilders,
    ) {}

    /**
     * @throws BuildException
     */
    public function build(Reference $reference, Ast $ast): WebonyxType
    {
        $builtType = null;

        foreach ($this->nodeTypeBuilders as $nodeTypeBuilder) {
            if (!$nodeTypeBuilder->supports($reference)) {
                continue;
            }

            $builtType = $nodeTypeBuilder->build($reference, $this, $ast);
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
