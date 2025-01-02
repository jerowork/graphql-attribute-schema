<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use ReflectionNamedType;

trait GetTypeIdTrait
{
    /**
     * @return class-string|null
     */
    public function getTypeId(ReflectionNamedType $type): ?string
    {
        if ($type->isBuiltin()) {
            return null;
        }

        /** @var class-string */
        return $type->getName();
    }
}
