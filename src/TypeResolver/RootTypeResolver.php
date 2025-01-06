<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver;

use BackedEnum;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
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
        if (!$this->container->has($node->getType()->id)) {
            throw ResolveException::nodeTypeNotInContainer($node->getType());
        }

        return function ($rootValue, array $args) use ($node, $ast) {
            /** @var array<string, mixed> $args */
            return $this->container->get($node->getType()->id)->{$node->methodName}(
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

        $node = $ast->getNodeByType($child->getType());

        if ($node === null) {
            throw ResolveException::logicError(sprintf('Node not found for typeId %s', $child->getType()->id));
        }

        if ($node instanceof EnumNode) {
            /** @var class-string<BackedEnum> $type */
            $type = $node->getType()->id;
            /** @var string $value */
            $value = $args[$child->name];

            return $type::from($value);
        }

        if ($node instanceof InputTypeNode) {
            /** @var array<string, mixed> $childArgs */
            $childArgs = $args[$child->name];
            $type = $child->getType()->id;

            return new $type(...array_map(
                fn($fieldNode) => $this->resolveChild($fieldNode, $childArgs, $ast),
                $node->fieldNodes,
            ));
        }

        throw ResolveException::logicError(sprintf('Node %s cannot be handled', $node->getType()->id));
    }
}
