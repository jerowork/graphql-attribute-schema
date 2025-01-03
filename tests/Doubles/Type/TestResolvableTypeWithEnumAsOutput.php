<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;

#[Type]
final readonly class TestResolvableTypeWithEnumAsOutput
{
    #[Field]
    public function getName(string $name): TestEnumType
    {
        return TestEnumType::from($name);
    }
}
