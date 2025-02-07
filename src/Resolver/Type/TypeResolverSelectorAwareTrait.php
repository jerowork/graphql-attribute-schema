<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use LogicException;

/**
 * @internal
 */
trait TypeResolverSelectorAwareTrait
{
    private TypeResolverSelector $typeResolverSelector;

    public function setTypeResolverSelector(TypeResolverSelector $typeResolverSelector): void
    {
        $this->typeResolverSelector = $typeResolverSelector;
    }

    public function getTypeResolverSelector(): TypeResolverSelector
    {
        if (!isset($this->typeResolverSelector)) {
            throw new LogicException('TypeResolverSelector must be set');
        }

        return $this->typeResolverSelector;
    }
}
