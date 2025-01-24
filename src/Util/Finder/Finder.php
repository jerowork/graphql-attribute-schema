<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Util\Finder;

use Generator;

/**
 * @internal
 */
interface Finder
{
    /**
     * @return Generator<string>
     */
    public function findFiles(string ...$dirs): Generator;
}
