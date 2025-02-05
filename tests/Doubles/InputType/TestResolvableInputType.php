<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;

#[InputType]
final readonly class TestResolvableInputType
{
    /**
     * @param list<string> $parentNames
     */
    public function __construct(
        #[Field]
        public string $name,
        #[Field(type: new ListType(ScalarType::String))]
        public array $parentNames,
        #[Field]
        public DateTimeImmutable $date,
    ) {}
}
