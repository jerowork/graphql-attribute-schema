<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

interface BaseAttribute
{
    public function getName(): ?string;

    public function getDescription(): ?string;
}
