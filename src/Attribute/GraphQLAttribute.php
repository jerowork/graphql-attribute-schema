<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

interface GraphQLAttribute
{
    public ?string $name {
        get;
    }
    public ?string $description {
        get;
    }
}
