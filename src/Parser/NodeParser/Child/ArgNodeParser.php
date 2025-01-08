<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\GetTypeTrait;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use ReflectionParameter;

final readonly class ArgNodeParser
{
    use GetTypeTrait;

    /**
     * @throws ParseException
     */
    public function parse(ReflectionParameter $parameter, ?Arg $attribute): ArgNode
    {
        $type = $this->getType($parameter->getType(), $attribute);

        if ($type === null) {
            throw ParseException::invalidParameterType($parameter->getName());
        }

        return new ArgNode(
            $type,
            $attribute->name ?? $parameter->getName(),
            $attribute?->description,
            $parameter->getName(),
        );
    }
}
