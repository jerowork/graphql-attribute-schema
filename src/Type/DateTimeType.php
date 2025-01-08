<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Type;

use DateTimeImmutable;
use DateTimeInterface;
use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;

/**
 * @implements ScalarType<DateTimeImmutable>
 */
#[Scalar(name: 'DateTime', description: 'Date and time (ISO-8601)', alias: DateTimeImmutable::class)]
final readonly class DateTimeType implements ScalarType
{
    public static function serialize(mixed $value): string
    {
        return $value->format(DateTimeInterface::ATOM);
    }

    public static function deserialize(string $value): DateTimeImmutable
    {
        return new DateTimeImmutable($value);
    }
}
