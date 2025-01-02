<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use Exception;

final class SchemaBuildException extends Exception
{
    public static function missingQueries(): self
    {
        return new self('No queries defined');
    }

    public static function missingMutations(): self
    {
        return new self('No mutations defined');
    }
}
