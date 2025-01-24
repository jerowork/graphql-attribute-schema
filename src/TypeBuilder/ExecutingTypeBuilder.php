<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\TypeBuilder;

use GraphQL\Type\Definition\Type as WebonyxType;
use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ListableTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;

/**
 * @internal
 */
final readonly class ExecutingTypeBuilder
{
    /**
     * @param iterable<TypeBuilder<TypeReference>> $typeBuilders
     */
    public function __construct(
        private iterable $typeBuilders,
    ) {}

    /**
     * @throws BuildException
     */
    public function build(TypeReference $reference, Ast $ast): WebonyxType
    {
        $builtType = null;

        foreach ($this->typeBuilders as $typeBuilder) {
            if (!$typeBuilder->supports($reference)) {
                continue;
            }

            $builtType = $typeBuilder->build($reference, $this, $ast);
        }

        if ($builtType === null) {
            throw BuildException::logicError(sprintf('Cannot build type for reference: %s', $reference::class));
        }

        if (!$reference->isValueNullable()) {
            $builtType = WebonyxType::nonNull($builtType); // @phpstan-ignore-line
        }

        if ($reference instanceof ListableTypeReference && $reference->isList()) {
            $builtType = WebonyxType::listOf($builtType);

            if (!$reference->isListNullable()) {
                $builtType = WebonyxType::nonNull($builtType);
            }
        }

        return $builtType;
    }
}
