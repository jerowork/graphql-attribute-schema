<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use ReflectionNamedType;

trait GetTypeTrait
{
    public function getType(ReflectionNamedType $type): Type
    {
        if ($type->isBuiltin()) {
            return Type::createScalar($type->getName());
        }

        return Type::createObject($type->getName());
    }
}
