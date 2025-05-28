<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Generator;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentsNodeParser;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use Override;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use Stringable;

/**
 * @internal
 */
final readonly class QueryNodeParser implements NodeParser
{
    use RetrieveNameForFieldTrait;
    use GetAttributeTrait;

    private const array ALLOWED_SCALAR_TYPES_FOR_DEFERRED_TYPE_LOADER = ['string', 'int', 'array'];

    public function __construct(
        private TypeReferenceDecider $typeReferenceDecider,
        private MethodArgumentsNodeParser $methodArgumentsNodeParser,
    ) {}

    #[Override]
    public function parse(string $attribute, ReflectionClass $class, ?ReflectionMethod $method): Generator
    {
        if ($attribute !== Query::class) {
            return;
        }

        if ($method === null) {
            throw new ParseException('Logic: Missing ReflectionMethod');
        }

        $attribute = $this->getAttribute($method, Query::class);
        $returnType = $method->getReturnType();

        $reference = $this->typeReferenceDecider->getTypeReference($returnType, $attribute);

        if ($reference === null) {
            throw ParseException::invalidReturnType($class->getName(), $method->getName());
        }

        // When reference is ConnectionType, the query needs to have Connection as return type
        if ($reference instanceof ConnectionTypeReference) {
            if (!$returnType instanceof ReflectionNamedType || $returnType->getName() !== Connection::class) {
                throw ParseException::invalidConnectionReturnType($class->getName(), $method->getName());
            }
        }

        // When it has a deferred type loader, the return type needs to be an integer, string or Stringable
        if ($attribute->deferredTypeLoader !== null) {
            if ($returnType === null) {
                throw ParseException::missingDeferredTypeLoaderReturnType($class->getName(), $method->getName());
            }

            if ($returnType instanceof ReflectionNamedType
                && $returnType->isBuiltin()
                && !in_array($returnType->getName(), self::ALLOWED_SCALAR_TYPES_FOR_DEFERRED_TYPE_LOADER, true)
            ) {
                throw ParseException::invalidDeferredTypeLoaderReturnType($class->getName(), $method->getName());
            }

            if (!$returnType instanceof Stringable) {
                throw ParseException::invalidDeferredTypeLoaderReturnType($class->getName(), $method->getName());
            }
        }

        yield new QueryNode(
            $class->getName(),
            $this->retrieveNameForField($method, $attribute),
            $attribute->description,
            array_values([...$this->methodArgumentsNodeParser->parse($method)]),
            $reference,
            $method->getName(),
            $attribute->deprecationReason,
            $attribute->deferredTypeLoader,
        );
    }
}
