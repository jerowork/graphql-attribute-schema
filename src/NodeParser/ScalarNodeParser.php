<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Generator;
use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Type\ScalarType;
use Override;
use ReflectionClass;
use ReflectionMethod;

/**
 * @internal
 */
final readonly class ScalarNodeParser implements NodeParser
{
    use GetAttributeTrait;
    use RetrieveNameForTypeTrait;

    /**
     * @throws ParseException
     */
    #[Override]
    public function parse(string $attribute, ReflectionClass $class, ?ReflectionMethod $method): Generator
    {
        if ($attribute !== Scalar::class) {
            return;
        }

        if (!$class->implementsInterface(ScalarType::class)) {
            throw ParseException::missingImplements($class->getName(), ScalarType::class);
        }

        $attribute = $this->getAttribute($class, Scalar::class);

        yield new ScalarNode(
            $class->getName(),
            $this->retrieveNameForType($class, $attribute),
            $attribute->description,
            $attribute->alias,
        );
    }
}
