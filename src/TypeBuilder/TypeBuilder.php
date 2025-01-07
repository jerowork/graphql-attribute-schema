<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\NullableType as WebonyxNullableType;
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
    public function build(Type $type, Ast $ast): WebonyxType
    {
        $builtType = null;

        if ($type->isScalar()) {
            $builtType = $this->buildScalar($type->value);
        }

        if ($type->isObject()) {
            /** @var class-string $value */
            $value = $type->value;
            $builtType = $this->buildObject($value, $ast);
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
        if (array_key_exists($className, $this->builtTypes)) {
            return $this->builtTypes[$className];
        }

        $node = $ast->getNodeByClassName($className);

        if ($node === null) {
            throw BuildException::logicError(sprintf('No node found for class: %s', $className));
        }

        foreach ($this->objectTypeBuilders as $objectTypeBuilder) {
            if (!$objectTypeBuilder->supports($node)) {
                continue;
            }

            $this->builtTypes[$className] = $objectTypeBuilder->build($node, $this, $ast);

            return $this->builtTypes[$className];
        }

        throw BuildException::logicError(sprintf('Invalid object class %s', $className));
    }
}
