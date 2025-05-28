<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\Type;
use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredTypeLoader;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Query implements NamedAttribute, TypedAttribute
{
    /**
     * @param null|class-string|Type|ScalarType $type
     * @param null|class-string<DeferredTypeLoader> $deferredTypeLoader
     */
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public null|ScalarType|string|Type $type = null,
        public ?string $deprecationReason = null,
        public ?string $deferredTypeLoader = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): null|ScalarType|string|Type
    {
        return $this->type;
    }
}
