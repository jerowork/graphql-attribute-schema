<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Resolver\Type;

use BackedEnum;
use Closure;
use GraphQL\Type\Definition\EnumType;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ListableTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\TypeReference;
use LogicException;
use Override;

/**
 * @internal
 */
final class EnumTypeResolver implements TypeResolver
{
    use TypeResolverSelectorAwareTrait;
    use GetNodeFromReferenceTrait;

    public function __construct(
        private readonly AstContainer $astContainer,
    ) {}

    #[Override]
    public function supports(TypeReference $reference): bool
    {
        return $reference instanceof ObjectTypeReference && $this->astContainer->getAst()->getNodeByClassName($reference->className) instanceof EnumNode;
    }

    #[Override]
    public function createType(TypeReference $reference): EnumType
    {
        $node = $this->getNodeFromReference($reference, $this->astContainer->getAst(), EnumNode::class);

        return new EnumType([
            'name' => $node->name,
            'description' => $node->description,
            'values' => $this->getValues($node),
        ]);
    }

    /**
     * @return null|list<int|string>|int|string
     */
    #[Override]
    public function resolve(TypeReference $reference, Closure $callback): null|array|int|string
    {
        if ($reference instanceof ListableTypeReference && $reference->isList()) {
            /** @var list<BackedEnum> $enums */
            $enums = $callback();

            foreach ($enums as $enum) {
                if (!$enum instanceof BackedEnum) {
                    throw new LogicException('Enum must be a BackedEnum');
                }
            }

            return array_map(fn($enum) => $enum->value, $enums);
        }

        $enum = $callback();

        if ($enum === null) {
            return null;
        }

        if (!$enum instanceof BackedEnum) {
            throw new LogicException('Enum must be a BackedEnum');
        }

        return $enum->value;
    }

    /**
     * @return null|list<BackedEnum>|BackedEnum
     */
    #[Override]
    public function abstract(ArgumentNode|FieldNode $node, array $args): null|array|BackedEnum
    {
        if (!$node instanceof FieldNode && !$node instanceof ArgNode) {
            throw new LogicException(sprintf('EnumType: Node must be either FieldNode or ArgNode, %s given', $node::class));
        }

        $enumNode = $this->getNodeFromReference($node->reference, $this->astContainer->getAst(), EnumNode::class);

        /** @var class-string<BackedEnum> $className */
        $className = $enumNode->className;

        if ($node->reference instanceof ListableTypeReference && $node->reference->isList()) {
            /** @var list<string> $value */
            $value = $args[$node->name];

            return array_map(fn($item) => $className::from($item), $value);
        }

        if (!array_key_exists($node->name, $args)) {
            return null;
        }

        /** @var string $value */
        $value = $args[$node->name];

        return $className::from($value);
    }

    /**
     * @return array<string, array{
     *     value: string,
     *     description: null|string,
     *     deprecationReason?: string
     * }>
     */
    private function getValues(EnumNode $node): array
    {
        $values = [];
        foreach ($node->cases as $case) {
            $value = [
                'value' => $case->value,
                'description' => $case->description,
            ];

            if ($case->deprecationReason !== null) {
                $value['deprecationReason'] = $case->deprecationReason;
            }

            $values[$case->value] = $value;
        }

        return $values;
    }
}
