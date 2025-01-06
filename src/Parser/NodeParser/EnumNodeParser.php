<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\EnumValue;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use ReflectionClass;
use BackedEnum;
use Override;
use ReflectionEnum;
use ReflectionException;
use UnitEnum;

final readonly class EnumNodeParser implements NodeParser
{
    use RetrieveNameForTypeTrait;
    use GetClassAttributeTrait;

    public function supports(string $attribute): bool
    {
        return $attribute === Enum::class;
    }

    /**
     * @throws ParseException
     * @throws ReflectionException
     */
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

        /** @var ReflectionClass<UnitEnum> $class */
        return new EnumNode(
            $className,
            $this->retrieveNameForType($class, $attribute),
            $attribute->getDescription(),
            $this->getValues($class),
        );
    }

    /**
     * @param ReflectionClass<UnitEnum> $class
     *
     * @throws ReflectionException
     *
     * @return list<EnumValueNode>
     */
    private function getValues(ReflectionClass $class): array
    {
        $cases = [];
        foreach ((new ReflectionEnum($class->getName()))->getCases() as $case) {
            $enumAttributes = $case->getAttributes(EnumValue::class);

            /** @var EnumValue|null $enumAttribute */
            $enumAttribute = $enumAttributes !== [] ? array_pop($enumAttributes)->newInstance() : null;

            /** @var BackedEnum $value */
            $value = $case->getValue();

            $cases[] = new EnumValueNode(
                (string) $value->value,
                $enumAttribute?->description,
            );
        }

        return $cases;
    }
}
