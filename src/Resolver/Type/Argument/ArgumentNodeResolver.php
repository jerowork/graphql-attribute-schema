<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type\Argument;

use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
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
     * @throws LogicException
     */
    public function resolve(ArgumentNode $argumentNode, array $args, TypeResolverSelector $typeResolverSelector): mixed
    {
        return match (true) {
            $argumentNode instanceof AutowireNode => $this->resolveAutowireNode($argumentNode),
            $argumentNode instanceof EdgeArgsNode => $this->resolveEdgeArgsNode($args),
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
     * @param array<string, mixed> $args
     */
    private function resolveArgNode(ArgNode $argNode, array $args, TypeResolverSelector $typeResolverSelector): mixed
    {
        return $typeResolverSelector->getResolver($argNode->reference)->abstract($argNode, $args);
    }
}
