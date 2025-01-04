<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgNodesParser;
use ReflectionClass;
use Override;

final readonly class MutationNodeParser implements NodeParser
{
    use RetrieveNameForResolverTrait;
    use GetMethodFromClassTrait;
    use GetTypeTrait;
    use IsRequiredTrait;
    use GetClassAttributeTrait;

    private const string RESOLVER_SUFFIX = 'Mutation';
    private const string RETURN_TYPE_VOID = 'void';

    public function __construct(
        private MethodArgNodesParser $methodArgNodesParser,
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

        if ($type->equals(Type::createScalar('void'))) {
            throw ParseException::voidReturnType($class->getName(), $method->getName());
        }

        return new MutationNode(
            Type::createObject($class->getName()),
            $this->retrieveNameForResolver($class, $attribute, self::RESOLVER_SUFFIX),
            $attribute->getDescription(),
            $this->methodArgNodesParser->parse($method),
            $type,
            $this->isRequired($method->getReturnType(), $attribute),
            $method->getName(),
        );
    }
}
