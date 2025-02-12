<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class AgentType implements UserType, RecipientType, SomeInterface
{
    public function __construct(
        string $recipientName,
        #[Field]
        public string $name,
        #[Field]
        public int $number,
    ) {}

    public function getId(): int
    {
        return 0;
    }

    #[Field]
    public function getRecipientId(): int
    {
        return 0;
    }

    #[Field]
    public function getOther(): string
    {
        return '';
    }
}
