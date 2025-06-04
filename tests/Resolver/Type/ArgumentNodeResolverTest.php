<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type;

use Exception;
use Jerowork\GraphqlAttributeSchema\Node\ArraySerializable;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgumentNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\ArgumentNodeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\BuiltInScalarTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelector;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Container\TestContainer;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;
use LogicException;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class ArgumentNodeResolverTest extends TestCase
{
    private TestContainer $container;
    private ArgumentNodeResolver $argumentNodeResolver;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->argumentNodeResolver = new ArgumentNodeResolver(
            $this->container = new TestContainer(),
        );
    }

    #[Test]
    public function itShouldResolveAutowireNode(): void
    {
        $this->container->set(stdClass::class, $service = new stdClass());

        $result = $this->argumentNodeResolver->resolve(new AutowireNode(
            stdClass::class,
            'propertyName',
        ), [], new TypeResolverSelector([]));

        self::assertSame($service, $result);
    }

    #[Test]
    public function itShouldResolveEdgeArgsNode(): void
    {
        $result = $this->argumentNodeResolver->resolve(
            new EdgeArgsNode('propertyName'),
            [
                'first' => 10,
                'after' => '2928a9e6-d5ef-4a11-9618-0a4ea27e84f1',
                'last' => null,
                'before' => null,
            ],
            new TypeResolverSelector([]),
        );

        self::assertEquals(new EdgeArgs(
            10,
            '2928a9e6-d5ef-4a11-9618-0a4ea27e84f1',
            null,
            null,
        ), $result);
    }

    #[Test]
    public function itShouldResolveArgNode(): void
    {
        $result = $this->argumentNodeResolver->resolve(
            new ArgNode(ScalarTypeReference::create('string'), 'argName', null, 'propertyName'),
            [
                'argName' => 'value',
            ],
            new TypeResolverSelector([
                new BuiltInScalarTypeResolver(),
            ]),
        );

        self::assertSame('value', $result);
    }

    #[Test]
    public function itShouldThrowWhenNotResolvable(): void
    {
        self::expectException(LogicException::class);
        self::expectExceptionMessage('Unknown argument node type');

        $this->argumentNodeResolver->resolve(new class implements ArgumentNode {
            public function toArray(): array
            {
                return [];
            }

            public static function fromArray(array $payload): ArraySerializable
            {
                throw new Exception();
            }
        }, [], new TypeResolverSelector([]));
    }
}
