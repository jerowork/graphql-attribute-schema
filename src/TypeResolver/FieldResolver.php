<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\OutputFieldResolver;
use Psr\Container\ContainerInterface;

/**
 * @internal
 */
final readonly class FieldResolver
{
    /**
     * @param iterable<OutputFieldResolver> $outputResolvers
     */
    public function __construct(
        private ContainerInterface $container,
        private iterable $outputResolvers,
    ) {}

    public function resolve(FieldNode $fieldNode, Ast $ast): callable
    {
        return function (object $object, array $args) use ($fieldNode, $ast) {
            if ($fieldNode->fieldType === FieldNodeType::Property) {
                return $this->resolveField(
                    $fieldNode,
                    fn() => $object->{$fieldNode->propertyName},
                    $ast,
                );
            }

            $arguments = [];
            foreach ($fieldNode->argumentNodes as $argumentNode) {
                if ($argumentNode instanceof AutowireNode) {
                    $arguments[] = $this->container->get($argumentNode->service);

                    continue;
                }

                if ($argumentNode instanceof EdgeArgsNode) {
                    /**
                     * @var array{
                     *     first?: null|int,
                     *     after?: null|string,
                     *     last?: null|int,
                     *     before?: null|string
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

                /** @var ArgNode $argumentNode */
                $arguments[] = $args[$argumentNode->name] ?? null;
            }

            return $this->resolveField(
                $fieldNode,
                fn() => $object->{$fieldNode->methodName}(...$arguments),
                $ast,
            );
        };
    }

    /**
     * @throws ResolveException
     */
    private function resolveField(FieldNode $field, callable $fieldCallback, Ast $ast): mixed
    {
        foreach ($this->outputResolvers as $outputResolver) {
            if (!$outputResolver->supports($field, $ast)) {
                continue;
            }

            return $outputResolver->resolve($field, $fieldCallback, $ast);
        }

        // Fallback
        return $fieldCallback();
    }
}
