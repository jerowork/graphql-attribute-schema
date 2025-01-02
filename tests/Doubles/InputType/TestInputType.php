<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;

#[InputType(name: 'TestInput', description: 'Test Input')]
final readonly class TestInputType {}
