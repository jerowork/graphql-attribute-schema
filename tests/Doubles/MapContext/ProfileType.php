<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\MapContext;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\MapContext;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Context;

#[Type]
final readonly class ProfileType
{
    public function __construct(
        #[Field]
        public string $id,
    ) {}

    #[Field]
    public function displayName(
        #[MapContext]
        CurrentUser $user,
    ): string {
        return $user->name;
    }

    /**
     * Reads the whole Context object (injected by type, no attribute needed).
     */
    #[Field]
    public function contextUserId(
        Context $context,
    ): string {
        return $context->getObject(CurrentUser::class)->id;
    }
}
