<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\AliasedNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\ObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;

/**
 * @implements NodeTypeBuilder<ObjectReference>
 */
final readonly class ObjectNodeTypeBuilder implements NodeTypeBuilder
{
    /**
     * @param iterable<ObjectTypeBuilder<Node>> $objectTypeBuilders
     */
    public function __construct(
        private BuiltTypesRegistry $builtTypesRegistry,
        private iterable $objectTypeBuilders,
    ) {}

    public function supports(Reference $reference): bool
    {
        return $reference instanceof ObjectReference;
    }

    public function build(Reference $reference, TypeBuilder $typeBuilder, Ast $ast): Type
    {
        $node = $ast->getNodeByClassName($reference->className);

        if ($node === null) {
            throw BuildException::logicError(sprintf('No node found for class: %s', $reference->className));
        }

        if ($node instanceof AliasedNode && $node->getAlias() !== null) {
            $nodeClassName = $node->getAlias();
        } else {
            $nodeClassName = $node->getClassName();
        }

        if ($this->builtTypesRegistry->hasType($nodeClassName)) {
            return $this->builtTypesRegistry->getType($nodeClassName);
        }

        foreach ($this->objectTypeBuilders as $objectTypeBuilder) {
            if (!$objectTypeBuilder->supports($node)) {
                continue;
            }

            $builtType = $objectTypeBuilder->build($node, $typeBuilder, $ast);

            $this->builtTypesRegistry->addType($nodeClassName, $builtType);

            return $builtType;
        }

        throw BuildException::logicError(sprintf('Invalid object class %s', $nodeClassName));
    }
}
