<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver;

use GraphQL\Type\Definition\EnumType;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use LogicException;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class BuiltTypesRegistryTest extends TestCase
{
    private BuiltTypesRegistry $registry;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = new BuiltTypesRegistry();
    }

    #[Test]
    public function itShouldThrowExceptionIfTypeIsNotInRegistry(): void
    {
        self::expectException(LogicException::class);

        $this->registry->getType('enum');
    }

    #[Test]
    public function itShouldSetTypeInRegistry(): void
    {
        $type = new EnumType(['name' => 'EnumType', 'values' => []]);

        $this->registry->addType('enum', $type);

        self::assertTrue($this->registry->hasType('enum'));
        self::assertSame($type, $this->registry->getType('enum'));
    }
}
