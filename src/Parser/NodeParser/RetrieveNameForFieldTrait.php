<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\GraphQLAttribute;
use ReflectionMethod;

trait RetrieveNameForFieldTrait
{
    private const string PREFIX = 'get';

    public function retrieveNameForField(ReflectionMethod $method, GraphQLAttribute $attribute): string
    {
        return $attribute->name ?? $this->retrieveFromMethod($method);
    }

    private function retrieveFromMethod(ReflectionMethod $method): string
    {
        $name = $method->getName();

        if (str_starts_with($name, self::PREFIX)) {
            $name = substr($name, strlen(self::PREFIX));
        }

        return strtolower(substr($name, 0, 1)) . substr($name, 1);
    }
}
