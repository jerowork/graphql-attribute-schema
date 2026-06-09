<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser\Child;

use DateTime;
use Jerowork\GraphqlAttributeSchema\Node\Child\MapContextNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MapContextNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestTypeWithMapContext;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionParameter;

/**
 * @internal
 */
final class MapContextNodeParserTest extends TestCase
{
    private MapContextNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new MapContextNodeParser();
    }

    #[Test]
    public function itShouldReturnNullIfParameterHasNoMapContextAttribute(): void
    {
        self::assertNull($this->parser->parse($this->parameterOf('withoutMapContext')));
    }

    #[Test]
    public function itShouldParseNonNullableMappedContext(): void
    {
        $node = $this->parser->parse($this->parameterOf('mappedContext'));

        self::assertEquals(new MapContextNode(
            DateTime::class,
            'service',
            false,
        ), $node);
    }

    #[Test]
    public function itShouldParseNullableMappedContext(): void
    {
        $node = $this->parser->parse($this->parameterOf('nullableMappedContext'));

        self::assertEquals(new MapContextNode(
            DateTime::class,
            'service',
            true,
        ), $node);
    }

    #[Test]
    public function itShouldGuardNonClassParameterType(): void
    {
        self::expectException(ParseException::class);

        $this->parser->parse($this->parameterOf('invalidMappedContext'));
    }

    private function parameterOf(string $method): ReflectionParameter
    {
        $parameters = (new ReflectionClass(TestTypeWithMapContext::class))
            ->getMethod($method)
            ->getParameters();

        $parameter = array_pop($parameters);
        self::assertInstanceOf(ReflectionParameter::class, $parameter);

        return $parameter;
    }
}
