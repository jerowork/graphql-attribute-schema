<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type\Connection;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;

/**
 * @internal
 */
final readonly class PageInfoTypeResolver
{
    public function __construct(
        private BuiltTypesRegistry $builtTypesRegistry,
    ) {}

    public function createPageInfo(): Type
    {
        $pageInfoName = 'PageInfo';

        if ($this->builtTypesRegistry->hasType($pageInfoName)) {
            return $this->builtTypesRegistry->getType($pageInfoName);
        }

        $pageInfo = new ObjectType([
            'name' => $pageInfoName,
            'fields' => [
                [
                    'name' => 'hasPreviousPage',
                    'type' => Type::nonNull(Type::boolean()),
                ],
                [
                    'name' => 'hasNextPage',
                    'type' => Type::nonNull(Type::boolean()),
                ],
                [
                    'name' => 'startCursor',
                    'type' => Type::string(),
                ],
                [
                    'name' => 'endCursor',
                    'type' => Type::string(),
                ],
            ],
        ]);

        $this->builtTypesRegistry->addType($pageInfoName, $pageInfo);

        return $pageInfo;
    }
}
