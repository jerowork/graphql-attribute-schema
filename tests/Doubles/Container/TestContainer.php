<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Container;

use Psr\Container\ContainerInterface;

final class TestContainer implements ContainerInterface
{
    /**
     * @var array<string, mixed>
     */
    public array $services = [];

    public function get(string $id): mixed
    {
        return $this->services[$id] ?? null;
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}
