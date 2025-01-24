<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetTypeReferenceTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use ReflectionParameter;

final readonly class ArgNodeParser
{
    use GetTypeReferenceTrait;

    /**
     * @throws ParseException
     */
    public function parse(ReflectionParameter $parameter): ArgNode
    {
        $attribute = $this->getAttribute($parameter, Arg::class);
        $reference = $this->getTypeReference($parameter->getType(), $attribute);

        if ($reference === null) {
            throw ParseException::invalidParameterType($parameter->getName());
        }

        return new ArgNode(
            $reference,
            $attribute->name ?? $parameter->getName(),
            $attribute?->description,
            $parameter->getName(),
        );
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $attributeName
     *
     * @return T
     */
    private function getAttribute(ReflectionParameter $parameter, string $attributeName): ?object
    {
        $attributes = $parameter->getAttributes($attributeName);

        if ($attributes === []) {
            return null;
        }

        return array_pop($attributes)->newInstance();
    }
}
