<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;
use LogicException;
use Psr\Container\ContainerInterface;

/**
 * @internal
 */
final readonly class FieldResolver
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    /**
     * @param list<FieldNode> $fieldNodes
     *
     * @return list<array{
     *     name: string,
     *     description: null|string,
     *     type: Type,
     *     args: list<array{
     *         name: string,
     *         description: null|string,
     *         type: Type
     *     }>,
     *     resolve: Closure,
     *     deprecationReason?: string
     * }>
     */
    public function getFields(array $fieldNodes, TypeResolverSelector $typeResolverSelector): array
    {
        $fields = [];

        foreach ($fieldNodes as $fieldNode) {
            $typeResolver = $typeResolverSelector->getResolver($fieldNode->reference);
            $type = $typeResolver->createType($fieldNode->reference);

            $field = [
                'name' => $fieldNode->name,
                'description' => $fieldNode->description,
                'type' => $type,
                'args' => [...$this->getArgs($fieldNode, $typeResolverSelector), ...$this->getConnectionArgs($fieldNode->reference)],
                'resolve' => $this->resolveField($fieldNode, $typeResolverSelector),
            ];

            if ($fieldNode->deprecationReason !== null) {
                $field['deprecationReason'] = $fieldNode->deprecationReason;
            }

            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * @return list<array{
     *     name: string,
     *     description: null|string,
     *     type: Type
     * }>
     */
    public function getArgs(FieldNode $fieldNode, TypeResolverSelector $typeResolverSelector): array
    {
        $args = [];
        foreach ($fieldNode->argumentNodes as $argumentNode) {
            if (!$argumentNode instanceof ArgNode) {
                continue;
            }

            $typeResolver = $typeResolverSelector->getResolver($argumentNode->reference);

            $args[] = [
                'name' => $argumentNode->name,
                'description' => $argumentNode->description,
                'type' => $typeResolver->createType($argumentNode->reference),
            ];
        }

        return $args;
    }

    /**
     * @return list<array{
     *     name: string,
     *     type: Type,
     *     description: string
     * }>
     */
    public function getConnectionArgs(TypeReference $reference): array
    {
        if (!$reference instanceof ConnectionTypeReference) {
            return [];
        }

        return [
            [
                'name' => 'first',
                'type' => Type::int(),
                'description' => 'Connection: return the first # items',
            ],
            [
                'name' => 'after',
                'type' => Type::string(),
                'description' => 'Connection: return items after cursor',
            ],
            [
                'name' => 'last',
                'type' => Type::int(),
                'description' => 'Connection: return the last # items',
            ],
            [
                'name' => 'before',
                'type' => Type::string(),
                'description' => 'Connection: return items before cursor',
            ],
        ];
    }

    public function resolveField(FieldNode $node, TypeResolverSelector $typeResolverSelector): Closure
    {
        if ($node->fieldType === FieldNodeType::Property) {
            return fn(object $object) => $typeResolverSelector
                ->getResolver($node->reference)
                ->resolve($node->reference, fn() => $object->{$node->propertyName});
        }

        // FieldNodeType::Method
        return function (object $object, array $args) use ($node, $typeResolverSelector): mixed {
            /** @var array<string, mixed> $args */
            $arguments = [];

            foreach ($node->argumentNodes as $argumentNode) {
                if ($argumentNode instanceof AutowireNode) {
                    $arguments[] = $this->container->get($argumentNode->service);

                    continue;
                }

                if ($argumentNode instanceof EdgeArgsNode) {
                    /**
                     * @var array{
                     *     first?: int,
                     *     after?: string,
                     *     last?: int,
                     *     before?: string
                     * } $args
                     */
                    $arguments[] = new EdgeArgs(
                        $args['first'] ?? null,
                        $args['after'] ?? null,
                        $args['last'] ?? null,
                        $args['before'] ?? null,
                    );

                    continue;
                }

                if ($argumentNode instanceof ArgNode) {
                    $arguments[] = $args[$argumentNode->name] ?? null;

                    continue;
                }

                throw new LogicException(sprintf('Unknown argument node type: %s', $argumentNode::class));
            }

            return $typeResolverSelector
                ->getResolver($node->reference)
                ->resolve($node->reference, fn() => $object->{$node->methodName}(...$arguments));
        };
    }
}
