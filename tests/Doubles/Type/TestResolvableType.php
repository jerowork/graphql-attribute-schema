<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class TestResolvableType
{
    #[Field]
    public function getName(string $name): string
    {
        return sprintf('GetName has been called with name %s', $name);
    }
}
