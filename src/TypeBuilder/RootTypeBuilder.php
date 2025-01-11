<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\QueryNode;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;

final readonly class RootTypeBuilder
{
    public function __construct(
        private TypeBuilder $typeBuilder,
        private RootTypeResolver $rootTypeResolver,
    ) {}

    /**
     * @return array{
     *     name: string,
     *     type: Type,
     *     args: list<array{
     *         name: string,
     *         type: Type,
     *         description: null|string
     *     }>,
     *     resolve: callable
     * }
     */
    public function build(QueryNode|MutationNode $node, Ast $ast): array
    {
        $args = [];
        foreach ($node->argNodes as $argNode) {
            $args[] = [
                'name' => $argNode->name,
                'type' => $this->typeBuilder->build($argNode->reference, $ast),
                'description' => $argNode->description,
            ];
        }

        $root = [
            'name' => $node->name,
            'type' => $this->typeBuilder->build($node->outputReference, $ast),
            'description' => $node->description,
            'args' => $args,
            'resolve' => $this->rootTypeResolver->resolve($node, $ast),
        ];

        if ($node->deprecationReason !== null) {
            $root['deprecationReason'] = $node->deprecationReason;
        }

        return $root;
    }
}
