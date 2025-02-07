<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

/**
 * @internal
 */
interface TypeResolverSelectorAware
{
    public function setTypeResolverSelector(TypeResolverSelector $typeResolverSelector): void;

    public function getTypeResolverSelector(): TypeResolverSelector;
}
