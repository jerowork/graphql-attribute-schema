<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\ObjectTypeBuilder;

final class TypeBuilder
{
    /**
     * @var array<class-string, WebonyxType>
     */
    private array $builtTypes = [];

    /**
     * @param iterable<ObjectTypeBuilder<Node>> $objectTypeBuilders
     */
    public function __construct(
        private readonly iterable $objectTypeBuilders,
    ) {}

    /**
     * @throws BuildException
     */
    public function build(Type $type, bool $isRequired, Ast $ast): WebonyxType
    {
        $builtType = null;

        if ($type->isScalar()) {
            $builtType = $this->buildScalar($type);
        }

        if ($type->isObject()) {
            $builtType = $this->buildObject($type, $ast);
        }

        if ($builtType === null) {
            throw BuildException::logicError('Missing type and typeId');
        }

        if ($isRequired) {
            /** @var WebonyxType&NullableType $builtType */
            return WebonyxType::nonNull($builtType);
        }

        return $builtType;
    }

    /**
     * @throws BuildException
     */
    private function buildScalar(Type $type): WebonyxType
    {
        return match ($type->id) {
            'string' => WebonyxType::string(),
            'int' => WebonyxType::int(),
            'float' => WebonyxType::float(),
            'bool' => WebonyxType::boolean(),
            default => throw BuildException::logicError(sprintf('Invalid type: %s', $type->id)),
        };
    }

    /**
     * @throws BuildException
     */
    private function buildObject(Type $type, Ast $ast): WebonyxType
    {
        /** @var class-string $typeName */
        $typeName = $type->id;

        if (array_key_exists($typeName, $this->builtTypes)) {
            return $this->builtTypes[$typeName];
        }

        $node = $ast->getNodeByType($type);

        if ($node === null) {
            throw BuildException::logicError(sprintf('No node found for type: %s', $typeName));
        }

        foreach ($this->objectTypeBuilders as $objectTypeBuilder) {
            if (!$objectTypeBuilder->supports($node)) {
                continue;
            }

            $this->builtTypes[$typeName] = $objectTypeBuilder->build($node, $this, $ast);

            return $this->builtTypes[$typeName];
        }

        throw BuildException::logicError(sprintf('Invalid object type: %s', $typeName));
    }
}
