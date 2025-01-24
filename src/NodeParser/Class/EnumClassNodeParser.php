<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\EnumValue;
use Jerowork\GraphqlAttributeSchema\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\Class\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Node\Node;
use Jerowork\GraphqlAttributeSchema\NodeParser\GetAttributeTrait;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\NodeParser\RetrieveNameForTypeTrait;
use ReflectionClass;
use BackedEnum;
use Override;
use ReflectionEnum;
use ReflectionException;

final readonly class EnumClassNodeParser implements ClassNodeParser
{
    use RetrieveNameForTypeTrait;
    use GetAttributeTrait;

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

        $attribute = $this->getAttribute($class, Enum::class);

        $name = $this->retrieveNameForType($class, $attribute);

        return new EnumNode(
            $className,
            $name,
            $attribute->getDescription(),
            $this->getValues($class),
        );
    }

    /**
     * @throws ReflectionException
     *
     * @return list<EnumValueNode>
     */
    private function getValues(ReflectionClass $class): array
    {
        $cases = [];
        /** @var class-string<BackedEnum> $enumClassName */
        $enumClassName = $class->getName();
        foreach ((new ReflectionEnum($enumClassName))->getCases() as $case) {
            $enumAttributes = $case->getAttributes(EnumValue::class);

            /** @var EnumValue|null $enumAttribute */
            $enumAttribute = $enumAttributes !== [] ? array_pop($enumAttributes)->newInstance() : null;

            /** @var BackedEnum $value */
            $value = $case->getValue();

            $cases[] = new EnumValueNode(
                (string) $value->value,
                $enumAttribute?->description,
                $enumAttribute?->deprecationReason,
            );
        }

        return $cases;
    }
}
