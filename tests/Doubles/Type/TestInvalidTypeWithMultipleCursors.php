<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Cursor;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class TestInvalidTypeWithMultipleCursors
{
    public function __construct(
        #[Cursor]
        public string $id,
    ) {}

    #[Cursor]
    public function getCursor(): string
    {
        return '';
    }
}
