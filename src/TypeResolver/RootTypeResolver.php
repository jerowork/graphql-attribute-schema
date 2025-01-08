<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input\InputChildResolver;
use Psr\Container\ContainerInterface;

final readonly class RootTypeResolver
{
    /**
     * @param iterable<InputChildResolver> $childResolvers
     */
    public function __construct(
        private ContainerInterface $container,
        private iterable $childResolvers,
    ) {}

    /**
     * @throws ResolveException
     */
    public function resolve(MutationNode|QueryNode $node, Ast $ast): callable
    {
        if (!$this->container->has($node->getClassName())) {
            throw ResolveException::nodeClassNameNotInContainer($node->getClassName());
        }

        return function ($rootValue, array $args) use ($node, $ast) {
            /** @var array<string, mixed> $args */
            return $this->container->get($node->getClassName())->{$node->methodName}(
                ...array_map(
                    fn($arg) => $this->resolveChild($arg, $args, $ast),
                    $node->argNodes,
                ),
            );
        };
    }

    /**
     * @param array<string, mixed> $args
     *
     * @throws ResolveException
     */
    public function resolveChild(ArgNode|FieldNode $child, array $args, Ast $ast): mixed
    {
        foreach ($this->childResolvers as $resolver) {
            if (!$resolver->supports($child, $ast)) {
                continue;
            }

            return $resolver->resolve($child, $args, $ast, $this);
        }

        throw ResolveException::logicError(sprintf('Child node %s cannot be handled', $child->name));
    }
}
