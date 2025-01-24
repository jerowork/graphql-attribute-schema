<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Scalar implements NamedAttribute
{
    /**
     * @param class-string|null $alias
     */
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public ?string $alias = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }
}
