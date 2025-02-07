<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type;

use Jerowork\GraphqlAttributeSchema\AstContainer;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\BuiltInScalarTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\EnumTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelector;
use LogicException;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class TypeResolverSelectorTest extends TestCase
{
    private TypeResolverSelector $selector;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->selector = new TypeResolverSelector([
            new BuiltInScalarTypeResolver(),
            new EnumTypeResolver(new AstContainer()),
        ]);
    }

    #[Test]
    public function itShouldThrowExceptionWhenNoResolverFound(): void
    {
        self::expectException(LogicException::class);

        $this->selector->getResolver(ConnectionTypeReference::create(stdClass::class, 10));
    }

    #[Test]
    public function itShouldGetResolver(): void
    {
        $reference = ScalarTypeReference::create('string');

        $resolver = $this->selector->getResolver($reference);

        self::assertInstanceOf(BuiltInScalarTypeResolver::class, $resolver);
    }
}
