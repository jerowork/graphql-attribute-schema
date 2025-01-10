<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ListableNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\NodeType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\NodeTypeBuilder;

final readonly class TypeBuilder
{
    /**
     * @param iterable<NodeTypeBuilder<NodeType>> $nodeTypeBuilders
     */
    public function __construct(
        private iterable $nodeTypeBuilders,
    ) {}

    /**
     * @throws BuildException
     */
    public function build(NodeType $type, Ast $ast): WebonyxType
    {
        $builtType = null;

        foreach ($this->nodeTypeBuilders as $nodeTypeBuilder) {
            if (!$nodeTypeBuilder->supports($type)) {
                continue;
            }

            $builtType = $nodeTypeBuilder->build($type, $this, $ast);
        }

        if ($builtType === null) {
            throw BuildException::logicError('Cannot build type');
        }

        if (!$type->isValueNullable()) {
            $builtType = WebonyxType::nonNull($builtType); // @phpstan-ignore-line
        }

        if ($type instanceof ListableNodeType && $type->isList()) {
            $builtType = WebonyxType::listOf($builtType);

            if (!$type->isListNullable()) {
                $builtType = WebonyxType::nonNull($builtType);
            }
        }

        return $builtType;
    }
}
