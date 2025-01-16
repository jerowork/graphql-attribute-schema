<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ConnectionReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;

trait GetConnectionArgsTrait
{
    /**
     * @return list<array{
     *     name: string,
     *     type: Type,
     *     description: string
     * }>
     */
    public function getConnectionArgs(Reference $reference): array
    {
        if (!$reference instanceof ConnectionReference) {
            return [];
        }

        return [
            [
                'name' => 'first',
                'type' => Type::int(),
                'description' => 'Connection: return the first # items',
                'defaultValue' => $reference->first,
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
}
