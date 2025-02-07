<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use Jerowork\GraphqlAttributeSchema\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Resolver\RootTypeResolver;

final readonly class SchemaBuilder
{
    public function __construct(
        private AstContainer $astContainer,
        private RootTypeResolver $rootTypeResolver,
    ) {}

    /**
     * @throws SchemaBuildException
     */
    public function build(Ast $ast): Schema
    {
        $this->astContainer->setAst($ast);

        $queries = array_map(fn($node) => $this->rootTypeResolver->createType($node), $ast->getNodesByNodeType(QueryNode::class));

        if ($queries === []) {
            throw SchemaBuildException::missingQueries();
        }

        $mutations = array_map(fn($node) => $this->rootTypeResolver->createType($node), $ast->getNodesByNodeType(MutationNode::class));

        if ($mutations === []) {
            throw SchemaBuildException::missingMutations();
        }

        return new Schema([
            'query' => new ObjectType([
                'name' => 'Query',
                'fields' => $queries,
            ]),
            'mutation' => new ObjectType([
                'name' => 'Mutation',
                'fields' => $mutations,
            ]),
        ]);
    }
}
