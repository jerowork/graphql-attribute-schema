<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use ReflectionParameter;
use ReflectionNamedType;

final readonly class AutowireNodeParser
{
    use GetAttributeTrait;

    /**
     * @throws ParseException
     */
    public function parse(ReflectionParameter $parameter): ?AutowireNode
    {
        try {
            $attribute = $this->getAttribute($parameter, Autowire::class);
        } catch (ParseException) {
            return null;
        }

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
