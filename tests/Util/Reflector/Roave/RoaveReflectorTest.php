<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Roave;

use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\Util\Reflector\Roave\RoaveReflector;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RoaveReflectorTest extends TestCase
{
    private RoaveReflector $reflector;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->reflector = new RoaveReflector();
    }

    #[Test]
    public function itShouldReflect(): void
    {
        $classes = $this->reflector->getClasses(__DIR__ . '/../../../Doubles/Type/TestType.php');

        $class = array_pop($classes);

        self::assertNotNull($class);
        self::assertSame(TestType::class, $class->getName());
    }
}
