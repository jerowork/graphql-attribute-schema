<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use ReflectionParameter;
use ReflectionNamedType;

final readonly class AutowireNodeParser
{
    /**
     * @throws ParseException
     */
    public function parse(ReflectionParameter $parameter): ?AutowireNode
    {
        $attribute = $this->getAttribute($parameter, Autowire::class);

        if ($attribute === null) {
            return null;
        }

        if ($attribute->service !== null) {
            return new AutowireNode(
                $attribute->service,
                $parameter->getName(),
            );
        }

        if (!$parameter->getType() instanceof ReflectionNamedType) {
            throw ParseException::invalidAutowiredParameterType($parameter->getName());
        }

        return new AutowireNode(
            $parameter->getType()->getName(),
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
