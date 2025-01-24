<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\NamedAttribute;
use ReflectionMethod;

trait RetrieveNameForFieldTrait
{
    private const string PREFIX = 'get';

    public function retrieveNameForField(ReflectionMethod $method, NamedAttribute $attribute): string
    {
        return $attribute->getName() ?? $this->retrieveFromMethod($method);
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
