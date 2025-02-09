<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Node;

use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;

/**
 * @phpstan-import-type ArgNodePayload from ArgNode
 * @phpstan-import-type EdgeArgsNodePayload from EdgeArgsNode
 *
 * @phpstan-type QueryNodePayload array{
 *     className: class-string,
 *     name: string,
 *     description: null|string,
 *     argumentNodes: list<array{
 *          node: class-string<ArgumentNode>,
 *          payload: ArgNodePayload|EdgeArgsNodePayload
 *     }>,
 *     outputReference: array{
 *          type: class-string,
 *          payload: array<string, mixed>
 *     },
 *     methodName: string,
 *     deprecationReason: null|string
 * }
 *
 * @internal
 */
final readonly class QueryNode implements Node
{
    /**
     * @param class-string $className
     * @param list<ArgumentNode> $argumentNodes
     */
    public function __construct(
        public string $className,
        public string $name,
        public ?string $description,
        public array $argumentNodes,
        public TypeReference $outputReference,
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
        $argumentNodes = [];
        foreach ($this->argumentNodes as $argumentNode) {
            $argumentNodes[] = [
                'node' => $argumentNode::class,
                'payload' => $argumentNode->toArray(),
            ];
        }

        return [
            'className' => $this->className,
            'name' => $this->name,
            'description' => $this->description,
            'argumentNodes' => $argumentNodes,
            'outputReference' => [
                'type' => $this->outputReference::class,
                'payload' => $this->outputReference->toArray(),
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
        $argumentNodes = [];
        foreach ($payload['argumentNodes'] as $argumentNode) {
            $argumentPayload = $argumentNode['payload'];

            if ($argumentNode['node'] === ArgNode::class) {
                /** @var ArgNodePayload $argumentPayload */
                $argumentNodes[] = ArgNode::fromArray($argumentPayload);
            } else {
                /** @var EdgeArgsNodePayload $argumentPayload */
                $argumentNodes[] = EdgeArgsNode::fromArray($argumentPayload);
            }
        }

        /** @var class-string<TypeReference> $type */
        $type = $payload['outputReference']['type'];

        return new self(
            $payload['className'],
            $payload['name'],
            $payload['description'],
            $argumentNodes,
            $type::fromArray($payload['outputReference']['payload']),
            $payload['methodName'],
            $payload['deprecationReason'],
        );
    }
}
