<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\NamedAttribute;
use ReflectionClass;

trait RetrieveNameForTypeTrait
{
    private const string NAME_TYPE_SUFFIX = 'Type';
    private const string VALID_REGEX = '/^[A-Z][a-zA-Z]+$/';

    /**
     * @throws ParseException
     */
    public function retrieveNameForType(ReflectionClass $class, NamedAttribute $attribute): string
    {
        $name = $attribute->getName() ?? $this->retrieveNameFromClass($class);

        if (preg_match(self::VALID_REGEX, $name) !== 1) {
            throw ParseException::invalidNameForType($name);
        }

        return $name;
    }

    private function retrieveNameFromClass(ReflectionClass $class): string
    {
        $parts = explode('\\', $class->getName());
        $name = array_pop($parts);

        if (str_ends_with($name, self::NAME_TYPE_SUFFIX)) {
            $name = substr($name, 0, -strlen(self::NAME_TYPE_SUFFIX));
        }

        return $name;
    }
}
