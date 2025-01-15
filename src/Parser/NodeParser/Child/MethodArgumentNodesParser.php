<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionNamedType;

final readonly class MethodArgumentNodesParser
{
    public function __construct(
        private AutowireNodeParser $autowireNodeParser,
        private EdgeArgsNodeParser $edgeArgsNodeParser,
        private ArgNodeParser $argNodeParser,
    ) {}

    /**
     * @throws ParseException
     *
     * @return list<ArgNode|AutowireNode|EdgeArgsNode>
     */
    public function parse(ReflectionMethod $method, bool $includeAutowireNodes = true): array
    {
        $argumentNodes = [];

        foreach ($method->getParameters() as $parameter) {
            if ($includeAutowireNodes) {
                $autowireAttribute = $this->getAttribute($parameter, Autowire::class);

                if ($autowireAttribute !== null) {
                    $argumentNodes[] = $this->autowireNodeParser->parse($parameter, $autowireAttribute);

                    continue;
                }
            }

            if ($parameter->getType() instanceof ReflectionNamedType && $parameter->getType()->getName() === EdgeArgs::class) {
                $argumentNodes[]  = $this->edgeArgsNodeParser->parse($parameter);

                continue;
            }

            $argAttribute = $this->getAttribute($parameter, Arg::class);

            $argumentNodes[] = $this->argNodeParser->parse($parameter, $argAttribute);
        }

        return $argumentNodes;
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
