<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Util\Reflector;

use Generator;
use ReflectionClass;

/**
 * @internal
 */
interface Reflector
{
    /**
     * @return Generator<ReflectionClass>
     */
    public function getClasses(string $filePath): Generator;
}
