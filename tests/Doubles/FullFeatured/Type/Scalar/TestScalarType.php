<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Scalar;

use DateTime;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;

#[Scalar(alias: DateTime::class)]
final class TestScalarType extends ScalarType
{
    public function serialize($value): string
    {
        return '';
    }

    public function parseValue($value): string
    {
        return '';
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null): string
    {
        return '';
    }
}
