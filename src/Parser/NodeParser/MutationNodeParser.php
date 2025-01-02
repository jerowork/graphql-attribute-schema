<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgNodesParser;
use ReflectionClass;
use Override;
use ReflectionNamedType;

final readonly class MutationNodeParser implements NodeParser
{
    use RetrieveNameForResolverTrait;
    use GetMethodFromClassTrait;
    use GetTypeTrait;
    use GetTypeIdTrait;
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
        $returnType = $method->getReturnType();

        if (!$returnType instanceof ReflectionNamedType) {
            throw ParseException::invalidReturnType($class->getName(), $method->getName());
        }

        if ($returnType->isBuiltin() && $returnType->getName() === self::RETURN_TYPE_VOID) {
            throw ParseException::voidReturnType($class->getName(), $method->getName());
        }

        $attribute = $this->getClassAttribute($class, Mutation::class);

        return new MutationNode(
            $class->getName(),
            $this->retrieveNameForResolver($class, $attribute, self::RESOLVER_SUFFIX),
            $attribute->description,
            $this->methodArgNodesParser->parse($method),
            $this->getTypeId($returnType),
            $this->getType($returnType),
            !$returnType->allowsNull(),
            $method->getName(),
        );
    }
}
