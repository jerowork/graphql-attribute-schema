<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;
use ReflectionMethod;
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
                $autowireNode = $this->autowireNodeParser->parse($parameter);

                if ($autowireNode !== null) {
                    $argumentNodes[] = $autowireNode;

                    continue;
                }
            }

            if ($parameter->getType() instanceof ReflectionNamedType && $parameter->getType()->getName() === EdgeArgs::class) {
                $argumentNodes[]  = $this->edgeArgsNodeParser->parse($parameter);

                continue;
            }

            $argumentNodes[] = $this->argNodeParser->parse($parameter);
        }

        return $argumentNodes;
    }
}
