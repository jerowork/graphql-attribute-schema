<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;

/**
 * @internal
 */
trait BuildArgsTrait
{
    /**
     * @return list<array{
     *     name: string,
     *     type: Type,
     *     description: null|string
     * }>
     */
    public function buildArgs(FieldNode $fieldNode, ExecutingTypeBuilder $typeBuilder, Ast $ast): array
    {
        $args = [];
        foreach ($fieldNode->argumentNodes as $argumentNode) {
            if (!$argumentNode instanceof ArgNode) {
                continue;
            }

            $args[] = [
                'name' => $argumentNode->name,
                'type' => $typeBuilder->build($argumentNode->reference, $ast),
                'description' => $argumentNode->description,
            ];
        }

        return $args;
    }
}
