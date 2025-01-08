<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgumentNodesParser;
use ReflectionClass;
use Override;

final readonly class MutationNodeParser implements NodeParser
{
    use RetrieveNameForResolverTrait;
    use GetMethodFromClassTrait;
    use GetTypeTrait;
    use GetClassAttributeTrait;

    private const string RESOLVER_SUFFIX = 'Mutation';

    public function __construct(
        private MethodArgumentNodesParser $methodArgumentNodesParser,
    ) {}

    #[Override]
    public function supports(string $attribute): bool
    {
        return $attribute === Mutation::class;
    }

    #[Override]
    public function parse(ReflectionClass $class): Node
    {
        $method = $this->getMethodFromClass($class);
        $attribute = $this->getClassAttribute($class, Mutation::class);

        $type = $this->getType($method->getReturnType(), $attribute);

        if ($type === null) {
            throw ParseException::invalidReturnType($class->getName(), $method->getName());
        }

        /** @var list<ArgNode> $argumentNodes */
        $argumentNodes = $this->methodArgumentNodesParser->parse($method, false);

        return new MutationNode(
            $class->getName(),
            $this->retrieveNameForResolver($class, $attribute, self::RESOLVER_SUFFIX),
            $attribute->getDescription(),
            $argumentNodes,
            $type,
            $method->getName(),
            $attribute->deprecationReason,
        );
    }
}
