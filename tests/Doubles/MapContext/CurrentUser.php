<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\MapContext;

/**
 * A plain context object (not a GraphQL type) attached to the Context per request.
 */
final readonly class CurrentUser
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
