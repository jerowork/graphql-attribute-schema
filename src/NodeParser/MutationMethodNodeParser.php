<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Method\MutationNode;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use ReflectionClass;
use Override;
use ReflectionMethod;
use ReflectionNamedType;

final readonly class MutationMethodNodeParser implements NodeParser
{
    use RetrieveNameForFieldTrait;
    use GetTypeReferenceTrait;
    use GetAttributeTrait;

    public function __construct(
        private MethodArgumentNodesParser $methodArgumentNodesParser,
    ) {}

    #[Override]
    public function supports(string $attribute): bool
    {
        return $attribute === Mutation::class;
    }

    #[Override]
    public function parse(ReflectionClass $class, ?ReflectionMethod $method): Node
    {
        if ($method === null) {
            throw new ParseException('Logic: Missing ReflectionMethod');
        }

        $attribute = $this->getAttribute($method, Mutation::class);
        $returnType = $method->getReturnType();

        $reference = $this->getTypeReference($returnType, $attribute);

        if ($reference === null) {
            throw ParseException::invalidReturnType($class->getName(), $method->getName());
        }

        // When reference is ConnectionType, the mutation needs to have Connection as return type
        if ($reference instanceof ConnectionTypeReference) {
            if (!$returnType instanceof ReflectionNamedType || $returnType->getName() !== Connection::class) {
                throw ParseException::invalidConnectionReturnType($class->getName(), $method->getName());
            }
        }

        /** @var list<ArgNode> $argumentNodes */
        $argumentNodes = $this->methodArgumentNodesParser->parse($method, false);

        return new MutationNode(
            $class->getName(),
            $this->retrieveNameForField($method, $attribute),
            $attribute->description,
            $argumentNodes,
            $reference,
            $method->getName(),
            $attribute->deprecationReason,
        );
    }
}
