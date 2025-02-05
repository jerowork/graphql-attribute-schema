<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser\Child;

use DateTime;
use Jerowork\GraphqlAttributeSchema\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestTypeWithAutowire;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionParameter;
use stdClass;

/**
 * @internal
 */
final class AutowireNodeParserTest extends TestCase
{
    private AutowireNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new AutowireNodeParser();
    }

    #[Test]
    public function itShouldReturnNullIfParameterHasNoAutowireAttribute(): void
    {
        $reflectionClass = new ReflectionClass(TestType::class);
        $reflectionMethod = $reflectionClass->getMethod('__construct');
        $parameters = $reflectionMethod->getParameters();
        /** @var ReflectionParameter $parameter */
        $parameter = array_pop($parameters);

        self::assertNull($this->parser->parse($parameter));
    }

    #[Test]
    public function itShouldParseWithCustomServiceId(): void
    {
        $reflectionClass = new ReflectionClass(TestTypeWithAutowire::class);
        $reflectionMethod = $reflectionClass->getMethod('serviceWithCustomId');
        $parameters = $reflectionMethod->getParameters();
        /** @var ReflectionParameter $parameter */
        $parameter = array_pop($parameters);

        $node = $this->parser->parse($parameter);

        self::assertEquals(new AutowireNode(
            stdClass::class,
            'service',
        ), $node);
    }

    #[Test]
    public function itShouldGuardTypeWhenParseWithoutCustomServiceId(): void
    {
        $reflectionClass = new ReflectionClass(TestTypeWithAutowire::class);
        $reflectionMethod = $reflectionClass->getMethod('invalidServiceWithoutCustomId');
        $parameters = $reflectionMethod->getParameters();
        /** @var ReflectionParameter $parameter */
        $parameter = array_pop($parameters);

        self::expectException(ParseException::class);

        $this->parser->parse($parameter);
    }

    #[Test]
    public function itShouldParseWithoutCustomServiceId(): void
    {
        $reflectionClass = new ReflectionClass(TestTypeWithAutowire::class);
        $reflectionMethod = $reflectionClass->getMethod('serviceWithoutCustomId');
        $parameters = $reflectionMethod->getParameters();
        /** @var ReflectionParameter $parameter */
        $parameter = array_pop($parameters);

        $node = $this->parser->parse($parameter);

        self::assertEquals(new AutowireNode(
            DateTime::class,
            'service',
        ), $node);
    }
}
