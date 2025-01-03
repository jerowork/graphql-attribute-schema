<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

interface GraphQLAttribute
{
    public function getName(): ?string;

    public function getDescription(): ?string;
}
