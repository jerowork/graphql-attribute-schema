<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use ReflectionMethod;
use ReflectionParameter;

final readonly class MethodArgumentNodesParser
{
    public function __construct(
        private AutowireNodeParser $autowireNodeParser,
        private ArgNodeParser $argNodeParser,
    ) {}

    /**
     * @throws ParseException
     *
     * @return list<ArgNode|AutowireNode>
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
