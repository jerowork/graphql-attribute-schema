<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;

/**
 * @phpstan-import-type ArgNodePayload from ArgNode
 * @phpstan-import-type TypePayload from Type
 *
 * @phpstan-type MutationNodePayload array{
 *     className: class-string,
 *     name: string,
 *     description: null|string,
 *     argNodes: list<ArgNodePayload>,
 *     outputType: TypePayload,
 *     methodName: string,
 *     deprecationReason: null|string
 * }
 */
final readonly class MutationNode implements Node
{
    /**
     * @param class-string $className
     * @param list<ArgNode> $argNodes
     */
    public function __construct(
        public string $className,
        public string $name,
        public ?string $description,
        public array $argNodes,
        public Type $outputType,
        public string $methodName,
        public ?string $deprecationReason,
    ) {}

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return MutationNodePayload
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
            'name' => $this->name,
            'description' => $this->description,
            'argNodes' => array_map(fn($argNode) => $argNode->toArray(), $this->argNodes),
            'outputType' => $this->outputType->toArray(),
            'methodName' => $this->methodName,
            'deprecationReason' => $this->deprecationReason,
        ];
    }

    /**
     * @param MutationNodePayload $payload
     */
    public static function fromArray(array $payload): MutationNode
    {
        return new self(
            $payload['className'],
            $payload['name'],
            $payload['description'],
            array_map(fn($argNodePayload) => ArgNode::fromArray($argNodePayload), $payload['argNodes']),
            Type::fromArray($payload['outputType']),
            $payload['methodName'],
            $payload['deprecationReason'],
        );
    }
}
