<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native;

use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\AnotherClass;
use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\AnotherEnum;
use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\AnotherInterface;
use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\FileWithClasses;
use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\SomeEnum;
use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\SomeInterface;
use Jerowork\GraphqlAttributeSchema\Util\Reflector\Native\NativeReflector;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class NativeReflectorTest extends TestCase
{
    private NativeReflector $reflector;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->reflector = new NativeReflector();
    }

    #[Test]
    public function itShouldGetAllClassesInFile(): void
    {
        $classes = $this->reflector->getClasses(__DIR__ . '/Doubles/FileWithClasses.php');

        $classNames = array_map(fn($class) => $class->getName(), $classes);

        self::assertSame([
            FileWithClasses::class,
            AnotherClass::class,
            SomeInterface::class,
            AnotherInterface::class,
            SomeEnum::class,
            AnotherEnum::class,
        ], $classNames);
    }
}
