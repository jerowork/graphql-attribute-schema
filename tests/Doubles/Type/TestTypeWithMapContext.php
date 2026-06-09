<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use DateTime;
use Jerowork\GraphqlAttributeSchema\Attribute\MapContext;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Context;

#[Type]
final readonly class TestTypeWithMapContext
{
    public function context(
        Context $context,
    ): void {}

    public function mappedContext(
        #[MapContext]
        DateTime $service,
    ): void {}

    public function nullableMappedContext(
        #[MapContext]
        ?DateTime $service,
    ): void {}

    public function invalidMappedContext(
        #[MapContext]
        int $service,
    ): void {}

    public function withoutMapContext(
        DateTime $service,
    ): void {}
}
