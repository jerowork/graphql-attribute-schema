<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use Closure;
use Exception;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ListableTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use LogicException;
use Override;

/**
 * @internal
 */
final class InputObjectTypeResolver implements TypeResolver
{
    use TypeResolverSelectorAwareTrait;
    use GetNodeFromReferenceTrait;

    public function __construct(
        private readonly AstContainer $astContainer,
        private readonly FieldResolver $fieldResolver,
    ) {}

    #[Override]
    public function supports(TypeReference $reference): bool
    {
        return $reference instanceof ObjectTypeReference && $this->astContainer->getAst()->getNodeByClassName($reference->className) instanceof InputTypeNode;
    }

    #[Override]
    public function createType(TypeReference $reference): InputObjectType
    {
        $node = $this->getNodeFromReference($reference, $this->astContainer->getAst(), InputTypeNode::class);

        return new InputObjectType([
            'name' => $node->name,
            'description' => $node->description,
            'fields' => fn() => $this->getFields($node),
        ]);
    }

    #[Override]
    public function resolve(TypeReference $reference, Closure $callback): mixed
    {
        return $callback();
    }

    #[Override]
    public function abstract(ArgumentNode|FieldNode $node, array $args): mixed
    {
        if (!$node instanceof FieldNode && !$node instanceof ArgNode) {
            throw new LogicException(sprintf('InputObjectType: Node must be either FieldNode or ArgNode, %s given', $node::class));
        }

        $inputTypeNode = $this->getNodeFromReference($node->reference, $this->astContainer->getAst(), InputTypeNode::class);

        $className = $inputTypeNode->className;

        if ($node->reference instanceof ListableTypeReference && $node->reference->isList()) {
            /** @var list<array<string, mixed>> $nodeArgs */
            $nodeArgs = $args[$node->name];

            return array_map(
                fn($item) => new $className(...array_map(
                    fn(FieldNode $fieldNode) => $this->getTypeResolverSelector()
                        ->getResolver($fieldNode->reference)
                        ->abstract($fieldNode, $item),
                    $inputTypeNode->fieldNodes,
                )),
                $nodeArgs,
            );
        }

        if (!array_key_exists($node->name, $args)) {
            return null;
        }

        /** @var array<string, mixed> $nodeArgs */
        $nodeArgs = $args[$node->name];

        return new $className(...array_map(
            fn($fieldNode) => $this->getTypeResolverSelector()
                ->getResolver($fieldNode->reference)
                ->abstract($fieldNode, $nodeArgs),
            $inputTypeNode->fieldNodes,
        ));
    }

    /**
     * @throws Exception
     *
     * @return list<array{
     *     name: string,
     *     description: null|string,
     *     type: Closure(): Type,
     *     args: list<array{
     *         name: string,
     *         description: null|string,
     *         type: Closure(): Type
     *     }>
     * }>
     */
    private function getFields(InputTypeNode $node): array
    {
        $fields = [];

        foreach ($node->fieldNodes as $fieldNode) {
            $typeResolver = $this->getTypeResolverSelector()->getResolver($fieldNode->reference);
            $type = $typeResolver->createType($fieldNode->reference);

            $fields[] = [
                'name' => $fieldNode->name,
                'description' => $fieldNode->description,
                'type' => fn() => $type,
                'args' => $this->fieldResolver->getArgs($fieldNode, $this->getTypeResolverSelector()),
            ];
        }

        return $fields;
    }
}
