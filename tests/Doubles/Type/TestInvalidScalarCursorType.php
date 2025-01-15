<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Cursor;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class TestInvalidScalarCursorType
{
    public function __construct(
        public string $id,
        #[Cursor]
        public int $cursor,
    ) {}
}
