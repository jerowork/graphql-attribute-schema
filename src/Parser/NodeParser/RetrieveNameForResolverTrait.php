<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\BaseAttribute;
use ReflectionMethod;

trait RetrieveNameForResolverTrait
{
    private const string VALID_REGEX = '/^[a-z][a-zA-Z]+$/';

    /**
     * @throws ParseException
     */
    public function retrieveNameForResolver(ReflectionMethod $method, BaseAttribute $attribute): string
    {
        $name = $attribute->getName() ?? $method->getName();

        if (preg_match(self::VALID_REGEX, $name) !== 1) {
            throw ParseException::invalidNameForResolver($name);
        }

        return $name;
    }
}
