<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Jerowork\GraphqlAttributeSchema\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\Resolver\RootTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelector;

final readonly class SchemaBuilder
{
    public function __construct(
        private AstContainer $astContainer,
        private BuiltTypesRegistry $builtTypesRegistry,
        private TypeResolverSelector $typeResolverSelector,
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
            'types' => $this->getTypesImplementingInterface(),
        ]);
    }

    /**
     * Get all types implementing an interface.
     * When an implementation is not defined in a schema, it's not automatically loaded by the resolvers.
     * Therefore, load all interface implementation types on the root schema level as well.
     *
     * @return iterable<Closure(): Type>
     */
    private function getTypesImplementingInterface(): iterable
    {
        foreach ($this->astContainer->getAst()->getNodesImplementingInterface() as $node) {
            $typeReference = ObjectTypeReference::create($node->getClassName());

            if ($this->builtTypesRegistry->hasType($node->getClassName())) {
                continue;
            }

            $type = $this->typeResolverSelector->getResolver($typeReference)->createType($typeReference);

            if ($type instanceof NonNull) {
                $type = $type->getWrappedType();
            }

            if ($type instanceof ListOfType) {
                $type = $type->getWrappedType();

                if ($type instanceof NonNull) {
                    $type = $type->getWrappedType();
                }
            }

            $this->builtTypesRegistry->addType($node->getClassName(), $type);

            yield fn() => $type;
        }
    }
}
