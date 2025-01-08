<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

/**
 * @phpstan-type ScalarNodePayload array{
 *     className: class-string,
 *     name: string,
 *     description: null|string,
 *     alias: null|class-string
 * }
 */
final readonly class ScalarNode implements Node, AliasedNode
{
    /**
     * @param class-string $className
     * @param class-string|null $alias
     */
    public function __construct(
        public string $className,
        public string $name,
        public ?string $description,
        public ?string $alias,
    ) {}

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @return ScalarNodePayload
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
            'name' => $this->name,
            'description' => $this->description,
            'alias' => $this->alias,
        ];
    }

    /**
     * @param ScalarNodePayload $payload
     */
    public static function fromArray(array $payload): ScalarNode
    {
        return new self(
            $payload['className'],
            $payload['name'],
            $payload['description'],
            $payload['alias'],
        );
    }
}
