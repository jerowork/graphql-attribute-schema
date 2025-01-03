<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use ReflectionClass;
use BackedEnum;
use Override;

final readonly class EnumNodeParser implements NodeParser
{
    use RetrieveNameForTypeTrait;
    use GetClassAttributeTrait;

    public function supports(string $attribute): bool
    {
        return $attribute === Enum::class;
    }

    #[Override]
    public function parse(ReflectionClass $class): Node
    {
        $className = $class->getName();

        if (!$class->isEnum()) {
            throw ParseException::notAnEnumClass($className);
        }

        if (!is_subclass_of($className, BackedEnum::class)) {
            throw ParseException::notABackedEnumClass($className);
        }

        $attribute = $this->getClassAttribute($class, Enum::class);

        return new EnumNode(
            Type::createObject($className),
            $this->retrieveNameForType($class, $attribute),
            $attribute->getDescription(),
            array_map(fn($case) => (string) $case->value, $className::cases()),
        );
    }
}
