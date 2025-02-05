<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Util\Reflector\Roave;

use Jerowork\GraphqlAttributeSchema\Util\Reflector\Reflector;
use Override;
use ReflectionClass;
use ReflectionException;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;

/**
 * @internal
 */
final readonly class RoaveReflector implements Reflector
{
    /**
     * @param non-empty-string $filePath
     *
     * @throws ReflectionException
     */
    #[Override]
    public function getClasses(string $filePath): array
    {
        $reflector = new DefaultReflector(new SingleFileSourceLocator(
            $filePath,
            (new BetterReflection())->astLocator(),
        ));

        return array_map(
            fn($reflectionClass) => new ReflectionClass($reflectionClass->getName()),
            $reflector->reflectAllClasses(),
        );
    }
}
