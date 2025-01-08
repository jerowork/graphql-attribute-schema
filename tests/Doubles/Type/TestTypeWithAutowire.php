<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use stdClass;
use DateTime;

#[Type]
final readonly class TestTypeWithAutowire
{
    // @phpstan-ignore-next-line
    public function serviceWithCustomId(
        #[Autowire(service: stdClass::class)]
        $service,
    ): void {}

    // @phpstan-ignore-next-line
    public function invalidServiceWithoutCustomId(
        #[Autowire]
        $service,
    ): void {}

    public function serviceWithoutCustomId(
        #[Autowire]
        DateTime $service,
    ): void {}
}
