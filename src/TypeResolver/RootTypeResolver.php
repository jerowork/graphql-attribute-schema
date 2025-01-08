<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver;

use BackedEnum;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\QueryNode;
use Psr\Container\ContainerInterface;

final readonly class RootTypeResolver
{
    public function __construct(
        private ContainerInterface $container,
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
        if ($child->type->isScalar()) {
            return $args[$child->name];
        }

        $node = $ast->getNodeByClassName($child->type->value);

        if ($node === null) {
            throw ResolveException::logicError(sprintf('Node not found for typeId %s', $child->type->value));
        }

        if ($node instanceof ScalarNode) {
            return $args[$child->name];
        }

        if ($node instanceof EnumNode) {
            /** @var class-string<BackedEnum> $className */
            $className = $node->getClassName();

            if ($child->type->isList()) {
                /** @var list<string> $value */
                $value = $args[$child->name];

                return array_map(fn($item) => $className::from($item), $value);
            }

            /** @var string $value */
            $value = $args[$child->name];

            return $className::from($value);
        }

        if ($node instanceof InputTypeNode) {
            $className = $child->type->value;

            if ($child->type->isList()) {
                /** @var list<array<string, mixed>> $childArgs */
                $childArgs = $args[$child->name];

                return array_map(
                    fn($item) => new $className(...array_map(
                        fn($fieldNode) => $this->resolveChild($fieldNode, $item, $ast),
                        $node->fieldNodes,
                    )),
                    $childArgs,
                );
            }

            /** @var array<string, mixed> $childArgs */
            $childArgs = $args[$child->name];

            return new $className(...array_map(
                fn($fieldNode) => $this->resolveChild($fieldNode, $childArgs, $ast),
                $node->fieldNodes,
            ));
        }

        throw ResolveException::logicError(sprintf('Node %s cannot be handled', $node->getClassName()));
    }
}
