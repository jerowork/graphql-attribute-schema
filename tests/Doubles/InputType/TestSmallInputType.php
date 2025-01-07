<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;

#[InputType]
final readonly class TestSmallInputType
{
    public function __construct(
        #[Field]
        public string $id,
    ) {}
}
