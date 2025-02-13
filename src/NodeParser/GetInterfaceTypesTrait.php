<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\InterfaceType;
use ReflectionClass;

trait GetInterfaceTypesTrait
{
    /**
     * @return list<class-string>
     */
    public function getInterfaceTypes(ReflectionClass $class): array
    {
        // Get all interfaces by native PHP interfaces (cascades implements on implements)
        $interfaces = array_values(array_map(
            fn(ReflectionClass $interface) => $interface->getName(),
            array_filter(
                $class->getInterfaces(),
                fn(ReflectionClass $interface) => $interface->getAttributes(InterfaceType::class) !== [],
            ),
        ));

        // Add all interface types via extends, including parent-parent
        return $this->addParentInterfaceType($interfaces, $class);
    }

    /**
     * @param list<class-string> $interfaces
     *
     * @return list<class-string>
     */
    private function addParentInterfaceType(array $interfaces, ?ReflectionClass $class): array
    {
        $extendsClass = $class?->getParentClass();

        if ($extendsClass instanceof ReflectionClass && $extendsClass->getAttributes(InterfaceType::class) !== []) {
            $interfaces[] = $extendsClass->getName();

            $interfaces = $this->addParentInterfaceType($interfaces, $extendsClass);
        }

        return $interfaces;
    }
}
