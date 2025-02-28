<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

use BackedEnum;
use Jerowork\GraphqlAttributeSchema\Node\Child\EnumValueNode;

/**
 * @phpstan-import-type EnumValueNodePayload from EnumValueNode
 *
 * @phpstan-type EnumNodePayload array{
 *     className: class-string<BackedEnum>,
 *     name: string,
 *     description: null|string,
 *     cases: list<EnumValueNodePayload>
 * }
 *
 * @internal
 */
final readonly class EnumNode implements Node
{
    /**
     * @param class-string<BackedEnum> $className
     * @param list<EnumValueNode> $cases
     */
    public function __construct(
        public string $className,
        public string $name,
        public ?string $description,
        public array $cases,
    ) {}

    /**
     * @return class-string<BackedEnum>
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return EnumNodePayload
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
            'name' => $this->name,
            'description' => $this->description,
            'cases' => array_map(fn($case) => $case->toArray(), $this->cases),
        ];
    }

    /**
     * @param EnumNodePayload $payload
     */
    public static function fromArray(array $payload): EnumNode
    {
        return new self(
            $payload['className'],
            $payload['name'],
            $payload['description'],
            array_map(fn($casePayload) => EnumValueNode::fromArray($casePayload), $payload['cases']),
        );
    }
}
