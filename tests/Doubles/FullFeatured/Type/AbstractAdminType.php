<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InterfaceType;

#[InterfaceType]
abstract readonly class AbstractAdminType implements RecipientType
{
    public function __construct(
        #[Field]
        public string $adminName,
    ) {}

    #[Field]
    abstract public function getPassword(): string;

    #[Field]
    public function isAdmin(): bool
    {
        return true;
    }
}
