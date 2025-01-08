<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\AutowireNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestTypeWithAutowire;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\AutowireNodeParser;
use Override;
use ReflectionClass;
use stdClass;
use ReflectionParameter;
use DateTime;

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
    public function itShouldParseWithCustomServiceId(): void
    {
        $reflectionClass = new ReflectionClass(TestTypeWithAutowire::class);
        $reflectionMethod = $reflectionClass->getMethod('serviceWithCustomId');
        $parameters = $reflectionMethod->getParameters();
        $parameter = array_pop($parameters);

        /** @var ReflectionParameter $parameter */
        $attributes = $parameter->getAttributes(Autowire::class);

        self::assertNotEmpty($attributes);
        $attribute = array_pop($attributes)->newInstance();

        $node = $this->parser->parse($parameter, $attribute);

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
        $parameter = array_pop($parameters);

        /** @var ReflectionParameter $parameter */
        $attributes = $parameter->getAttributes(Autowire::class);

        self::assertNotEmpty($attributes);
        $attribute = array_pop($attributes)->newInstance();

        self::expectException(ParseException::class);

        $this->parser->parse($parameter, $attribute);
    }

    #[Test]
    public function itShouldParseWithoutCustomServiceId(): void
    {
        $reflectionClass = new ReflectionClass(TestTypeWithAutowire::class);
        $reflectionMethod = $reflectionClass->getMethod('serviceWithoutCustomId');
        $parameters = $reflectionMethod->getParameters();
        $parameter = array_pop($parameters);

        /** @var ReflectionParameter $parameter */
        $attributes = $parameter->getAttributes(Autowire::class);

        self::assertNotEmpty($attributes);
        $attribute = array_pop($attributes)->newInstance();

        $node = $this->parser->parse($parameter, $attribute);

        self::assertEquals(new AutowireNode(
            DateTime::class,
            'service',
        ), $node);
    }
}
