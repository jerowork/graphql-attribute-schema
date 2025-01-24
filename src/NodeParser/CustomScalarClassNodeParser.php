<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;
use Jerowork\GraphqlAttributeSchema\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\Type\ScalarType;
use ReflectionClass;
use ReflectionMethod;

final readonly class CustomScalarClassNodeParser implements NodeParser
{
    use GetAttributeTrait;
    use RetrieveNameForTypeTrait;

    public function supports(string $attribute): bool
    {
        return $attribute === Scalar::class;
    }

    /**
     * @throws ParseException
     */
    public function parse(ReflectionClass $class, ?ReflectionMethod $method): Node
    {
        if (!$class->implementsInterface(ScalarType::class)) {
            throw ParseException::missingImplements($class->getName(), ScalarType::class);
        }

        $attribute = $this->getAttribute($class, Scalar::class);

        return new CustomScalarNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->description,
            $attribute->alias,
        );
    }
}
