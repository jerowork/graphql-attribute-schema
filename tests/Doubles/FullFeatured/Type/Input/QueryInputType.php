<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarStatusType;

#[InputType]
final readonly class QueryInputType
{
    public function __construct(
        #[Field(name: 'queryId', description: 'Query id')]
        public string $id,
        #[Field]
        public FoobarStatusType $status,
    ) {}
}
