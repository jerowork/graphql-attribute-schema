<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\MapContext;
use Jerowork\GraphqlAttributeSchema\Node\Child\MapContextNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use ReflectionNamedType;
use ReflectionParameter;

/**
 * @internal
 */
final readonly class MapContextNodeParser
{
    use GetAttributeTrait;

    /**
     * @throws ParseException
     */
    public function parse(ReflectionParameter $parameter): ?MapContextNode
    {
        try {
            $this->getAttribute($parameter, MapContext::class);
        } catch (ParseException) {
            return null;
        }

        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
            throw ParseException::invalidMapContextParameterType($parameter->getName());
        }

        /** @var class-string $className */
        $className = $type->getName();

        return new MapContextNode(
            $className,
            $parameter->getName(),
            $type->allowsNull(),
        );
    }
}
