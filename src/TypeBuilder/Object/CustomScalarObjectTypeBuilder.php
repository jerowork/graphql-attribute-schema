<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder\Object;

use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Type\ScalarType;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;

/**
 * @implements ObjectTypeBuilder<ScalarNode>
 */
final readonly class CustomScalarObjectTypeBuilder implements ObjectTypeBuilder
{
    public function supports(Node $node): bool
    {
        return $node instanceof ScalarNode;
    }

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
