<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class TestInvalidConnectionMethodType
{
    /**
     * @return array<mixed>
     */
    #[Field(type: new ConnectionType(TestType::class))]
    public function edges(): array
    {
        return [];
    }
}
