<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use ReflectionClass;

interface ClassNodeParser
{
    /**
     * @param class-string $attribute
     */
    public function supports(string $attribute): bool;

    /**
     * @throws ParseException
     */
    public function parse(ReflectionClass $class): Node;
}
