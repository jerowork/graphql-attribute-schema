<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\MapContext;

use Jerowork\GraphqlAttributeSchema\Attribute\MapContext;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;

final readonly class ProfileQuery
{
    #[Query]
    public function profile(
        #[MapContext]
        CurrentUser $user,
    ): ProfileType {
        return new ProfileType($user->id);
    }
}
