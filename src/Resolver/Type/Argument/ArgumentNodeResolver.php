<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type\Argument;

use Jerowork\GraphqlAttributeSchema\Context;
use Jerowork\GraphqlAttributeSchema\ContextException;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ContextNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\MapContextNode;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelector;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;
use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @internal
 */
final readonly class ArgumentNodeResolver
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    /**
     * @param array<string, mixed> $args
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ContextException
     * @throws LogicException
     */
    public function resolve(ArgumentNode $argumentNode, array $args, TypeResolverSelector $typeResolverSelector, mixed $context): mixed
    {
        return match (true) {
            $argumentNode instanceof AutowireNode => $this->resolveAutowireNode($argumentNode),
            $argumentNode instanceof EdgeArgsNode => $this->resolveEdgeArgsNode($args),
            $argumentNode instanceof ContextNode => $this->resolveContextNode($context),
            $argumentNode instanceof MapContextNode => $this->resolveMapContextNode($argumentNode, $context),
            $argumentNode instanceof ArgNode => $this->resolveArgNode($argumentNode, $args, $typeResolverSelector),
            default => throw new LogicException(sprintf('Unknown argument node type: %s', $argumentNode::class)),
        };
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function resolveAutowireNode(AutowireNode $autowireNode): mixed
    {
        return $this->container->get($autowireNode->service);
    }

    /**
     * @param array{
     *     first?: int,
     *     after?: string,
     *     last?: int,
     *     before?: string
     * } $args
     */
    private function resolveEdgeArgsNode(array $args): EdgeArgs
    {
        return new EdgeArgs(
            $args['first'] ?? null,
            $args['after'] ?? null,
            $args['last'] ?? null,
            $args['before'] ?? null,
        );
    }

    /**
     * @throws ContextException
     */
    private function resolveContextNode(mixed $context): Context
    {
        if (!$context instanceof Context) {
            throw ContextException::contextNotInjectable();
        }

        return $context;
    }

    /**
     * @throws ContextException
     */
    private function resolveMapContextNode(MapContextNode $mapContextNode, mixed $context): ?object
    {
        if (!$context instanceof Context) {
            throw ContextException::contextNotAvailable($mapContextNode->className);
        }

        return $mapContextNode->nullable
            ? $context->maybeGetObject($mapContextNode->className)
            : $context->getObject($mapContextNode->className);
    }

    /**
     * @param array<string, mixed> $args
     */
    private function resolveArgNode(ArgNode $argNode, array $args, TypeResolverSelector $typeResolverSelector): mixed
    {
        return $typeResolverSelector->getResolver($argNode->reference)->abstract($argNode, $args);
    }
}
