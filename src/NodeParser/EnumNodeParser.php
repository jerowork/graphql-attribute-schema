<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\EnumValue;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EnumValueNode;
use ReflectionClass;
use BackedEnum;
use Override;
use ReflectionEnum;
use ReflectionException;
use ReflectionMethod;
use Generator;

/**
 * @internal
 */
final readonly class EnumNodeParser implements NodeParser
{
    use RetrieveNameForTypeTrait;
    use GetAttributeTrait;

    /**
     * @throws ParseException
     * @throws ReflectionException
     */
    #[Override]
    public function parse(string $attribute, ReflectionClass $class, ?ReflectionMethod $method): Generator
    {
        if ($attribute !== Enum::class) {
            return;
        }

        $className = $class->getName();

        if (!$class->isEnum()) {
            throw ParseException::notAnEnumClass($className);
        }

        if (!is_subclass_of($className, BackedEnum::class)) {
            throw ParseException::notABackedEnumClass($className);
        }

        $attribute = $this->getAttribute($class, Enum::class);

        $name = $this->retrieveNameForType($class, $attribute);

        yield new EnumNode(
            $className,
            $name,
            $attribute->description,
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
