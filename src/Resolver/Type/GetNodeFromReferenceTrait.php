<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use LogicException;

/**
 * @internal
 */
trait GetNodeFromReferenceTrait
{
    /**
     * @template T of Node
     *
     * @param class-string<T> $nodeClass
     *
     * @return T
     */
    public function getNodeFromReference(TypeReference $reference, Ast $ast, string $nodeClass): Node
    {
        if (!$reference instanceof ObjectTypeReference && !$reference instanceof ConnectionTypeReference) {
            throw new LogicException('Reference must implement ObjectTypeReference or ConnectionTypeReference');
        }

        $node = $ast->getNodeByClassName($reference->className);

        if ($node === null) {
            throw new LogicException('No node found for reference: ' . $reference->className);
        }

        if (!$node instanceof $nodeClass) {
            throw new LogicException(sprintf('Node %s must implement %s', $node->getClassName(), $nodeClass));
        }

        return $node;
    }
}
