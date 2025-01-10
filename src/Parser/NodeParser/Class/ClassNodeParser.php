<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use ReflectionClass;

interface ClassNodeParser
{
    /**
     * @param class-string $attribute
     */
    public function supports(string $attribute): bool;

    /**
     * @param ReflectionClass<object> $class
     *
     * @throws ParseException
     */
    public function parse(ReflectionClass $class): Node;
}
