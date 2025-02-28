<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Util\Reflector;

use ReflectionClass;

/**
 * @internal
 */
interface Reflector
{
    /**
     * @return list<ReflectionClass>
     */
    public function getClasses(string $filePath): array;
}
