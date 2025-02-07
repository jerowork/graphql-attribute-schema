<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarStatusType;

final readonly class BasicQuery
{
    #[Query(name: 'basicName', description: 'A description')]
    public function __invoke(
        int $id,
        ?string $name,
        bool $isTrue,
        FoobarStatusType $status,
    ): string {
        return 'string';
    }
}
