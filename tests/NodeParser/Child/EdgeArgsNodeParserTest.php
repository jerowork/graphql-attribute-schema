<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Node\Child\EdgeArgsNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestTypeWithAutowire;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Override;
use ReflectionClass;
use ReflectionParameter;

/**
 * @internal
 */
final class EdgeArgsNodeParserTest extends TestCase
{
    private EdgeArgsNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new EdgeArgsNodeParser();
    }

    #[Test]
    public function itShouldCreateEdgeArgsNode(): void
    {
        $reflectionClass = new ReflectionClass(TestTypeWithAutowire::class);
        $reflectionMethod = $reflectionClass->getMethod('serviceWithCustomId');
        $parameters = $reflectionMethod->getParameters();

        /** @var ReflectionParameter $parameter */
        $parameter = array_pop($parameters);

        $edgeArgsNode = $this->parser->parse($parameter);

        self::assertEquals(new EdgeArgsNode('service'), $edgeArgsNode);
    }
}
