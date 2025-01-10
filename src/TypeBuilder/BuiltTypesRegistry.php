<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\Type;

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

    /**
     * @throws BuildException
     */
    public function getType(string $key): Type
    {
        if (!$this->hasType($key)) {
            throw BuildException::logicError(sprintf('Type with key %s not found in registry', $key));
        }

        return $this->builtTypes[$key];
    }

    public function addType(string $key, Type $type): void
    {
        $this->builtTypes[$key] = $type;
    }
}
