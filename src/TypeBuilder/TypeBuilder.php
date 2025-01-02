<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\ObjectTypeBuilder;

final class TypeBuilder
{
    /**
     * @var array<class-string, Type>
     */
    private array $builtTypes = [];

    /**
     * @param iterable<ObjectTypeBuilder<Node>> $objectTypeBuilders
     */
    public function __construct(
        private readonly iterable $objectTypeBuilders,
    ) {}

    /**
     * @param class-string|null $typeId
     *
     * @throws BuildException
     */
    public function build(?string $type, ?string $typeId, bool $isRequired, Ast $ast): Type
    {
        $builtType = null;

        if ($type !== null) {
            $builtType = $this->buildScalar($type);
        }

        if ($typeId !== null) {
            $builtType = $this->buildObject($typeId, $ast);
        }

        if ($builtType === null) {
            throw BuildException::logicError('Missing type and typeId');
        }

        if ($isRequired) {
            /** @var Type&NullableType $builtType */
            return Type::nonNull($builtType);
        }

        return $builtType;
    }

    private function buildScalar(string $type): Type
    {
        return match ($type) {
            'string' => Type::string(),
            'int' => Type::int(),
            'float' => Type::float(),
            'bool' => Type::boolean(),
            default => throw BuildException::logicError(sprintf('Invalid type: %s', $type)),
        };
    }

    /**
     * @param class-string $typeId
     *
     * @throws BuildException
     */
    private function buildObject(string $typeId, Ast $ast): Type
    {
        if (array_key_exists($typeId, $this->builtTypes)) {
            return $this->builtTypes[$typeId];
        }

        $node = $ast->getNodeByTypeId($typeId);

        if ($node === null) {
            throw BuildException::logicError(sprintf('No node found for type: %s', $typeId));
        }

        foreach ($this->objectTypeBuilders as $objectTypeBuilder) {
            if (!$objectTypeBuilder->supports($node)) {
                continue;
            }

            $this->builtTypes[$typeId] = $objectTypeBuilder->build($node, $this, $ast);

            return $this->builtTypes[$typeId];
        }

        throw BuildException::logicError(sprintf('Invalid object type: %s', $typeId));
    }
}
