<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use DateTime;
use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use stdClass;

#[Type]
final readonly class TestTypeWithAutowire
{
    public function serviceWithCustomId(
        #[Autowire(service: stdClass::class)]
        $service,
    ): void {}

    public function invalidServiceWithoutCustomId(
        #[Autowire]
        $service,
    ): void {}

    public function serviceWithoutCustomId(
        #[Autowire]
        DateTime $service,
    ): void {}
}
