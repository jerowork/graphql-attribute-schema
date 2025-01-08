<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar;

use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;
use Jerowork\GraphqlAttributeSchema\Type\ScalarType;
use DateTime;

/**
 * @implements ScalarType<mixed>
 */
#[Scalar(alias: DateTime::class)]
final readonly class TestScalarType implements ScalarType
{
    public static function serialize(mixed $value): string
    {
        return '';
    }

    public static function deserialize(string $value): mixed
    {
        return '';
    }
}
