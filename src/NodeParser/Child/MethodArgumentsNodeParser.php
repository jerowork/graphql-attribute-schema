<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Child;

use Generator;
use Jerowork\GraphqlAttributeSchema\Context;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ContextNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * @internal
 */
final readonly class MethodArgumentsNodeParser
{
    public function __construct(
        private MapContextNodeParser $mapContextNodeParser,
        private AutowireNodeParser $autowireNodeParser,
        private EdgeArgsNodeParser $edgeArgsNodeParser,
        private ArgNodeParser $argNodeParser,
    ) {}

    /**
     * @throws ParseException
     *
     * @return Generator<ArgumentNode>
     */
    public function parse(ReflectionMethod $method): Generator
    {
        foreach ($method->getParameters() as $parameter) {
            $mapContextNode = $this->mapContextNodeParser->parse($parameter);

            if ($mapContextNode !== null) {
                yield $mapContextNode;

                continue;
            }

            $autowireNode = $this->autowireNodeParser->parse($parameter);

            if ($autowireNode !== null) {
                yield $autowireNode;

                continue;
            }

            if ($parameter->getType() instanceof ReflectionNamedType && $parameter->getType()->getName() === Context::class) {
                yield new ContextNode($parameter->getName());

                continue;
            }

            if ($parameter->getType() instanceof ReflectionNamedType && $parameter->getType()->getName() === EdgeArgs::class) {
                yield $this->edgeArgsNodeParser->parse($parameter);

                continue;
            }

            yield $this->argNodeParser->parse($parameter);
        }
    }
}
