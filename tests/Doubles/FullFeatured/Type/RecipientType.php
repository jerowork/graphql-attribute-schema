<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InterfaceType;

#[InterfaceType]
interface RecipientType
{
    #[Field]
    public function getRecipientId(): int;
}
