<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Cursor;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class TestMethodCursorType
{
    public function __construct(
        public string $id,
        public string $cursor,
    ) {}

    #[Cursor]
    public function getCursor(): string
    {
        return '';
    }
}
