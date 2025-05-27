<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Type;

use DateTimeImmutable;
use DateTimeInterface;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use InvalidArgumentException;
use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;

/**
 * @see https://webonyx.github.io/graphql-php/type-definitions/scalars/#writing-custom-scalar-types
 */
#[Scalar(name: 'DateTime', description: 'Date and time (ISO-8601)', alias: DateTimeImmutable::class)]
final class DateTimeType extends ScalarType
{
    public function serialize($value): string
    {
        if (!$value instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Expected a DateTimeImmutable value for custom scalar DateTimeType');
        }

        return $value->format(DateTimeInterface::ATOM);
    }

    public function parseValue($value): DateTimeImmutable
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Expected a string value for custom scalar DateTimeType');
        }

        return new DateTimeImmutable($value);
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null): DateTimeImmutable
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new InvalidArgumentException('Expected a string value (node) for custom scalar DateTimeType');
        }

        return new DateTimeImmutable($valueNode->value);
    }
}
