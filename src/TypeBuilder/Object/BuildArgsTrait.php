<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Object;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\FieldNode;
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
        foreach ($fieldNode->argNodes as $argNode) {
            $args[] = [
                'name' => $argNode->name,
                'type' => $typeBuilder->build(
                    $argNode->type,
                    $argNode->typeId,
                    $argNode->isRequired,
                    $ast,
                ),
                'description' => $argNode->description,
            ];
        }

        return $args;
    }
}
