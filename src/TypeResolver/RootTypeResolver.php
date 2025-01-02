<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeResolver;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\QueryNode;
use Psr\Container\ContainerInterface;

final readonly class RootTypeResolver
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    public function resolve(MutationNode|QueryNode $node, Ast $ast): callable
    {
        return function ($rootValue, array $args) use ($node, $ast) {
            /** @var array<string, mixed> $args */
            return $this->container->get($node->typeId)->{$node->methodName}(
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
        if ($child->type !== null) {
            return $args[$child->name];
        }

        if ($child->typeId === null) {
            throw ResolveException::logicError(sprintf('TypeId for field %s cannot be null', $child->name));
        }

        $node = $ast->getNodeByTypeId($child->typeId);

        if ($node === null) {
            throw ResolveException::logicError(sprintf('Node %s not found for typeId', $child->typeId));
        }

        if ($node instanceof EnumNode) {
            return $node->typeId::from($args[$child->name]);
        }

        if ($node instanceof InputTypeNode) {
            /** @var array<string, mixed> $childArgs */
            $childArgs = $args[$child->name];

            return new $child->typeId(...array_map(
                fn($fieldNode) => $this->resolveChild($fieldNode, $childArgs, $ast),
                $node->fieldNodes,
            ));
        }

        throw ResolveException::logicError(sprintf('Node %s cannot be handled', $node->name));
    }
}
