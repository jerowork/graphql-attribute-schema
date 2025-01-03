<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\BaseAttribute;
use ReflectionClass;

trait RetrieveNameForResolverTrait
{
    private const string VALID_REGEX = '/^[a-z][a-zA-Z]+$/';

    /**
     * @param ReflectionClass<object> $class
     *
     * @throws ParseException
     */
    public function retrieveNameForResolver(ReflectionClass $class, BaseAttribute $attribute, string $suffix): string
    {
        $name = $attribute->getName() ?? $this->retrieveNameFromClass($class, $suffix);

        if (preg_match(self::VALID_REGEX, $name) !== 1) {
            throw ParseException::invalidNameForResolver($name);
        }

        return $name;
    }

    /**
     * @param ReflectionClass<object> $class
     */
    private function retrieveNameFromClass(ReflectionClass $class, string $suffix): string
    {
        $parts = explode('\\', $class->getName());
        $name = array_pop($parts);

        if (str_ends_with($name, $suffix)) {
            $name = substr($name, 0, -strlen($suffix));
        }

        return strtolower(substr($name, 0, 1)) . substr($name, 1);
    }
}
