<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;
use Jerowork\GraphqlAttributeSchema\Parser\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Type\ScalarType;
use ReflectionClass;

final readonly class ScalarNodeParser implements NodeParser
{
    use GetClassAttributeTrait;
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

        $attribute = $this->getClassAttribute($class, Scalar::class);

        return new ScalarNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->getDescription(),
            $attribute->alias,
        );
    }
}
