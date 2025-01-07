<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Object;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuildException;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Override;

/**
 * @implements ObjectTypeBuilder<InputTypeNode>
 */
final readonly class InputTypeObjectTypeBuilder implements ObjectTypeBuilder
{
    use BuildArgsTrait;

    #[Override]
    public function supports(Node $node): bool
    {
        return $node instanceof InputTypeNode;
    }

    /**
     * @throws BuildException
     */
    #[Override]
    public function build(Node $node, TypeBuilder $typeBuilder, Ast $ast): Type
    {
        return new InputObjectType([
            'name' => $node->name,
            'description' => $node->description,
            'fields' => $this->buildFields($node, $typeBuilder, $ast),
        ]);
    }

    /**
     * @throws BuildException
     *
     * @return list<array{
     *     name: string,
     *     type: InputType&Type,
     *     args: list<array{
     *         name: string,
     *         type: Type,
     *         description: null|string
     *     }>
     * }>
     */
    private function buildFields(InputTypeNode $node, TypeBuilder $typeBuilder, Ast $ast): array
    {
        $fields = [];

        foreach ($node->fieldNodes as $fieldNode) {
            $type = $typeBuilder->build($fieldNode->type, $ast);

            if (!$type instanceof InputType) {
                throw BuildException::invalidTypeForField($fieldNode->name, $type::class);
            }

            $fields[] = [
                'name' => $fieldNode->name,
                'type' => $type,
                'description' => $fieldNode->description,
                'args' => $this->buildArgs($fieldNode, $typeBuilder, $ast),
            ];
        }

        return $fields;
    }
}
