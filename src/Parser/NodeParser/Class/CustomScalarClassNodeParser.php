<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\RetrieveNameForTypeTrait;
use Jerowork\GraphqlAttributeSchema\Type\ScalarType;
use ReflectionClass;

final readonly class CustomScalarClassNodeParser implements ClassNodeParser
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
    public function parse(ReflectionClass $class): Node
    {
        if (!$class->implementsInterface(ScalarType::class)) {
            throw ParseException::missingImplements($class->getName(), ScalarType::class);
        }

        $attribute = $this->getAttribute($class, Scalar::class);

        return new CustomScalarNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->getDescription(),
            $attribute->alias,
        );
    }
}
