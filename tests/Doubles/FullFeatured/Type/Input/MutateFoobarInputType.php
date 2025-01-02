<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;

#[InputType(name: 'MutateFoobar')]
final readonly class MutateFoobarInputType
{
    public function __construct(
        #[Field]
        public int $id,
        #[Field]
        public ?string $value,
        #[Field]
        public Baz $baz,
        #[Field]
        public ?DateTimeImmutable $date,
    ) {}
}
