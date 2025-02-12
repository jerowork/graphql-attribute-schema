<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\AgentType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\UserType;

final readonly class WithInterfaceOutputQuery
{
    #[Query]
    public function withInterface(): UserType
    {
        return new AgentType('recipient-name', 'name', 1);
    }
}
