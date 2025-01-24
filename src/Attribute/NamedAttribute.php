<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

interface NamedAttribute
{
    public function getName(): ?string;
}
