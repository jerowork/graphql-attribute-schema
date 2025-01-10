<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Query;

final readonly class TestInvalidQueryWithInvalidReturnType
{
    #[Query]
    public function __invoke(): void {}
}
