<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetTypeReferenceTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use ReflectionParameter;

final readonly class ArgNodeParser
{
    use GetTypeReferenceTrait;
    use GetAttributeTrait;

    /**
     * @throws ParseException
     */
    public function parse(ReflectionParameter $parameter): ArgNode
    {
        try {
            $attribute = $this->getAttribute($parameter, Arg::class);
        } catch (ParseException) {
            $attribute = null;
        }

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
