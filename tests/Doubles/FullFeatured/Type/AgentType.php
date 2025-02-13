<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class AgentType extends AbstractAdminType implements UserType, SomeInterface
{
    public function __construct(
        string $recipientName,
        #[Field]
        public string $name,
        #[Field]
        public int $number,
    ) {
        parent::__construct($recipientName);
    }

    public function getId(): int
    {
        return 0;
    }

    #[Field]
    public function getRecipientId(): int
    {
        return 0;
    }

    public function getPassword(): string
    {
        return 'password';
    }

    #[Field]
    public function getOther(): string
    {
        return '';
    }
}
