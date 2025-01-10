<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\Node\Method;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\NodeType;

/**
 * @phpstan-import-type ArgNodePayload from ArgNode
 *
 * @phpstan-type QueryNodePayload array{
 *     className: class-string,
 *     name: string,
 *     description: null|string,
 *     argNodes: list<ArgNodePayload>,
 *     outputType: array{
 *          type: class-string,
 *          payload: array<string, mixed>
 *     },
 *     methodName: string,
 *     deprecationReason: null|string
 * }
 */
final readonly class QueryNode implements Node
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
        public NodeType $outputType,
        public string $methodName,
        public ?string $deprecationReason,
    ) {}

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return QueryNodePayload
     */
    public function toArray(): array
    {
        // @phpstan-ignore-next-line
        return [
            'className' => $this->className,
            'name' => $this->name,
            'description' => $this->description,
            'argNodes' => array_map(fn($argNode) => $argNode->toArray(), $this->argNodes),
            'outputType' => [
                'type' => $this->outputType::class,
                'payload' => $this->outputType->toArray(),
            ],
            'methodName' => $this->methodName,
            'deprecationReason' => $this->deprecationReason,
        ];
    }

    /**
     * @param QueryNodePayload $payload
     */
    public static function fromArray(array $payload): QueryNode
    {
        /** @var class-string<NodeType> $type */
        $type = $payload['outputType']['type'];

        return new self(
            $payload['className'],
            $payload['name'],
            $payload['description'],
            array_map(fn($argNodePayload) => ArgNode::fromArray($argNodePayload), $payload['argNodes']),
            $type::fromArray($payload['outputType']['payload']), // @phpstan-ignore-line
            $payload['methodName'],
            $payload['deprecationReason'],
        );
    }
}
