<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver;

use Closure;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\ArgumentNodeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Deferred\DeferredTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\FieldResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelector;
use Psr\Container\ContainerInterface;
use Stringable;

/**
 * Creates root types as Query and Mutation.
 *
 * @internal
 */
final readonly class RootTypeResolver
{
    public function __construct(
        private TypeResolverSelector $typeResolverSelector,
        private ContainerInterface $container,
        private FieldResolver $fieldResolver,
        private DeferredTypeResolver $deferredTypeResolver,
        private ArgumentNodeResolver $argumentNodeResolver,
    ) {}

    /**
     * @throws ResolveException
     *
     * @return array{
     *     name: string,
     *     description: null|string,
     *     type: Closure(): Type,
     *     args: list<array{
     *         name: string,
     *         description: null|string,
     *         type: Closure(): Type
     *     }>,
     *     resolve: Closure,
     *     deprecationReason?: string
     * }
     */
    public function createType(MutationNode|QueryNode $node): array
    {
        $typeResolver = $this->typeResolverSelector->getResolver($node->outputReference);

        $rootType = [
            'name' => $node->name,
            'description' => $node->description,
            'type' => fn() => $typeResolver->createType($node->outputReference),
            'args' => [...$this->createArgs($node), ...$this->fieldResolver->getConnectionArgs($node->outputReference)],
            'resolve' => $this->resolve($node),
        ];

        if ($node->deprecationReason !== null) {
            $rootType['deprecationReason'] = $node->deprecationReason;
        }

        return $rootType;
    }

    /**
     * @return list<array{
     *     name: string,
     *     description: null|string,
     *     type: Closure(): Type
     * }>
     */
    private function createArgs(MutationNode|QueryNode $node): array
    {
        return array_values(array_map(
            fn(ArgNode $argNode) => [
                'name' => $argNode->name,
                'description' => $argNode->description,
                'type' => fn() => $this->typeResolverSelector
                    ->getResolver($argNode->reference)
                    ->createType($argNode->reference),
            ],
            array_filter($node->argumentNodes, fn($argumentNode) => $argumentNode instanceof ArgNode),
        ));
    }

    /**
     * @throws ResolveException
     */
    private function resolve(MutationNode|QueryNode $node): Closure
    {
        if (!$this->container->has($node->className)) {
            throw ResolveException::rootTypeNotInContainer($node->className);
        }

        $resolver = $this->container->get($node->className);

        return function (mixed $rootValue, array $args) use ($resolver, $node): mixed {
            /** @var array<string, mixed> $args */
            $arguments = [];

            foreach ($node->argumentNodes as $argumentNode) {
                $arguments[] = $this->argumentNodeResolver->resolve($argumentNode, $args, $this->typeResolverSelector);
            }

            /** @var list<int|string|Stringable>|int|string|Stringable $result */
            $result = $resolver->{$node->methodName}(...$arguments);

            if ($node->deferredTypeLoader !== null) {
                return $this->deferredTypeResolver->resolve($node->deferredTypeLoader, $result);
            }

            return $result;
        };
    }
}
