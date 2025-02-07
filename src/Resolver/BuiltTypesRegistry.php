<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver;

use GraphQL\Type\Definition\Type;
use LogicException;

/**
 * @internal
 */
final class BuiltTypesRegistry
{
    /**
     * @var array<string, Type>
     */
    private array $builtTypes = [];

    public function hasType(string $key): bool
    {
        return array_key_exists($key, $this->builtTypes);
    }

    public function getType(string $key): Type
    {
        if (!$this->hasType($key)) {
            throw new LogicException(sprintf('Type with key %s not found in registry', $key));
        }

        return $this->builtTypes[$key];
    }

    public function addType(string $key, Type $type): void
    {
        $this->builtTypes[$key] = $type;
    }
}
