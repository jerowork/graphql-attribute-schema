<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use Override;

/**
 * @implements ObjectTypeBuilder<TypeNode>
 */
final readonly class TypeObjectTypeBuilder implements ObjectTypeBuilder
{
    use BuildArgsTrait;

    public function __construct(
        private FieldResolver $typeResolver,
    ) {}

    #[Override]
    public function supports(Node $node): bool
    {
        return $node instanceof TypeNode;
    }

    #[Override]
    public function build(Node $node, TypeBuilder $typeBuilder, Ast $ast): Type
    {
        return new ObjectType([
            'name' => $node->name,
            'description' => $node->description,
            'fields' => $this->buildFields($node, $typeBuilder, $ast),
        ]);
    }

    /**
     * @return list<array{
     *     name: string,
     *     type: Type,
     *     args: list<array{
     *         name: string,
     *         type: Type,
     *         description: null|string
     *     }>,
     *     resolve: callable
     * }>
     */
    private function buildFields(TypeNode $node, TypeBuilder $typeBuilder, Ast $ast): array
    {
        $fields = [];

        foreach ($node->fieldNodes as $fieldNode) {
            $field = [
                'name' => $fieldNode->name,
                'type' => $typeBuilder->build($fieldNode->reference, $ast),
                'description' => $fieldNode->description,
                'args' => $this->buildArgs($fieldNode, $typeBuilder, $ast),
                'resolve' => $this->typeResolver->resolve($fieldNode, $ast),
            ];

            if ($fieldNode->deprecationReason !== null) {
                $field['deprecationReason'] = $fieldNode->deprecationReason;
            }

            $fields[] = $field;
        }

        return $fields;
    }
}
