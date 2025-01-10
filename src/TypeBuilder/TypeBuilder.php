<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\NullableType as WebonyxNullableType;
use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\AliasedNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\NodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\ObjectTypeBuilder;

final readonly class TypeBuilder
{
    /**
     * @param iterable<ObjectTypeBuilder<Node>> $objectTypeBuilders
     */
    public function __construct(
        private BuiltTypesRegistry $builtTypesRegistry,
        private iterable $objectTypeBuilders,
    ) {}

    /**
     * @throws BuildException
     */
    public function build(NodeType $type, Ast $ast): WebonyxType
    {
        $builtType = null;

        if ($type instanceof ScalarNodeType) {
            $builtType = $this->buildScalar($type->value);
        }

        if ($type instanceof ObjectNodeType) {
            $builtType = $this->buildObject($type->className, $ast);
        }

        if ($builtType === null) {
            throw BuildException::logicError('Cannot build type');
        }

        if (!$type->isValueNullable()) {
            $builtType = WebonyxType::nonNull($builtType); // @phpstan-ignore-line
        }

        if ($type->isList()) {
            $builtType = WebonyxType::listOf($builtType);

            if (!$type->isListNullable()) {
                $builtType = WebonyxType::nonNull($builtType);
            }
        }

        return $builtType;
    }

    /**
     * @throws BuildException
     */
    private function buildScalar(string $type): WebonyxType&WebonyxNullableType
    {
        return match ($type) {
            'string' => WebonyxType::string(),
            'int' => WebonyxType::int(),
            'float' => WebonyxType::float(),
            'bool' => WebonyxType::boolean(),
            default => throw BuildException::logicError(sprintf('Invalid type: %s', $type)),
        };
    }

    /**
     * @param class-string $className
     *
     * @throws BuildException
     */
    private function buildObject(string $className, Ast $ast): WebonyxType
    {
        $node = $ast->getNodeByClassName($className);

        if ($node === null) {
            throw BuildException::logicError(sprintf('No node found for class: %s', $className));
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

            $builtType = $objectTypeBuilder->build($node, $this, $ast);

            $this->builtTypesRegistry->addType($nodeClassName, $builtType);

            return $builtType;
        }

        throw BuildException::logicError(sprintf('Invalid object class %s', $nodeClassName));
    }
}
