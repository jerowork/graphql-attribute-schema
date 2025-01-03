<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Parser\Node\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetTypeTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionNamedType;

final readonly class MethodArgNodesParser
{
    use GetTypeTrait;

    /**
     * @throws ParseException
     *
     * @return list<ArgNode>
     */
    public function parse(ReflectionMethod $method): array
    {
        $argNodes = [];

        foreach ($method->getParameters() as $parameter) {
            $argAttribute = $this->getArgAttribute($parameter);
            $parameterType = $parameter->getType();

            if (!$parameterType instanceof ReflectionNamedType) {
                throw ParseException::invalidParameterType($method->getName(), $parameter->getName());
            }

            $argNodes[] = new ArgNode(
                $this->getType($parameterType),
                $argAttribute->name ?? $parameter->getName(),
                $argAttribute?->description,
                !$parameterType->allowsNull(),
                $parameter->getName(),
            );
        }

        return $argNodes;
    }

    private function getArgAttribute(ReflectionParameter $parameter): ?Arg
    {
        $argAttributes = $parameter->getAttributes(Arg::class);

        if ($argAttributes === []) {
            return null;
        }

        return array_pop($argAttributes)->newInstance();
    }
}
