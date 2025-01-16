<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetTypeReferenceTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use ReflectionParameter;

final readonly class ArgNodeParser
{
    use GetTypeReferenceTrait;

    /**
     * @throws ParseException
     */
    public function parse(ReflectionParameter $parameter, ?Arg $attribute): ArgNode
    {
        $reference = $this->getTypeReference($parameter->getType(), $attribute);

        if ($reference === null) {
            throw ParseException::invalidParameterType($parameter->getName());
        }

        return new ArgNode(
            $reference,
            $attribute->name ?? $parameter->getName(),
            $attribute?->description,
            $parameter->getName(),
        );
    }
}
