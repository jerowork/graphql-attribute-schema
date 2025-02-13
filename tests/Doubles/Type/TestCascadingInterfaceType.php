<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\AbstractTestInterfaceType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\TestOtherInterfaceType;

#[Type]
final readonly class TestCascadingInterfaceType extends AbstractTestInterfaceType implements TestOtherInterfaceType
{
    public function getStatus(): ?string
    {
        return '';
    }

    public function getOtherName(): string
    {
        return '';
    }

    public function getSubName(): string
    {
        return '';
    }
}
