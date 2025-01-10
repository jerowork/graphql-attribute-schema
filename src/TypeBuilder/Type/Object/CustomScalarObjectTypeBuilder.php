<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object;

use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Type\ScalarType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Override;

/**
 * @implements ObjectTypeBuilder<CustomScalarNode>
 */
final readonly class CustomScalarObjectTypeBuilder implements ObjectTypeBuilder
{
    #[Override]
    public function supports(Node $node): bool
    {
        return $node instanceof CustomScalarNode;
    }

    #[Override]
    public function build(Node $node, TypeBuilder $typeBuilder, Ast $ast): Type
    {
        /** @var ScalarType<mixed> $scalarType */
        $scalarType = $node->className;

        // @phpstan-ignore-next-line
        return new CustomScalarType([
            'name' => $node->name,
            'serialize' => fn($value) => $scalarType::serialize($value),
            'parseValue' => fn(string $value) => $scalarType::deserialize($value),
            'parseLiteral' => fn(StringValueNode $valueNode) => $scalarType::deserialize($valueNode->value),
            'description' => $node->description,
        ]);
    }
}
