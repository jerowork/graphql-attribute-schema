<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use LogicException;

/**
 * @internal
 */
final readonly class TypeResolverSelector
{
    /**
     * @param iterable<TypeResolver> $typeResolvers
     */
    public function __construct(
        private iterable $typeResolvers,
    ) {}

    /**
     * @throws LogicException
     */
    public function getResolver(TypeReference $reference): TypeResolver
    {
        foreach ($this->typeResolvers as $typeResolver) {
            $typeResolver->setTypeResolverSelector($this);

            if (!$typeResolver->supports($reference)) {
                continue;
            }

            return $typeResolver;
        }

        throw new LogicException(sprintf('No TypeResolver found for reference %s', $reference::class));
    }
}
