<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Object;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;

trait BuildArgsTrait
{
    /**
     * @return list<array{
     *     name: string,
     *     type: Type,
     *     description: null|string
     * }>
     */
    public function buildArgs(FieldNode $fieldNode, TypeBuilder $typeBuilder, Ast $ast): array
    {
        $args = [];
        foreach ($fieldNode->argumentNodes as $argumentNode) {
            if (!$argumentNode instanceof ArgNode) {
                continue;
            }

            $args[] = [
                'name' => $argumentNode->name,
                'type' => $typeBuilder->build($argumentNode->type, $ast),
                'description' => $argumentNode->description,
            ];
        }

        return $args;
    }
}
