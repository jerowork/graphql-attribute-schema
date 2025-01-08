<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use ReflectionParameter;
use ReflectionNamedType;

final readonly class AutowireNodeParser
{
    /**
     * @throws ParseException
     */
    public function parse(ReflectionParameter $parameter, Autowire $attribute): AutowireNode
    {
        if ($attribute->service !== null) {
            return new AutowireNode(
                $attribute->service,
                $parameter->getName(),
            );
        }

        if (!$parameter->getType() instanceof ReflectionNamedType) {
            throw ParseException::invalidAutowiredParameterType($parameter->getName());
        }

        return new AutowireNode(
            $parameter->getType()->getName(),
            $parameter->getName(),
        );
    }
}
