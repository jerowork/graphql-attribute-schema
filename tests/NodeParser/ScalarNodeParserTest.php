<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use DateTime;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\NodeParser\ScalarNodeParser;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestInvalidScalarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestScalarType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @internal
 */
final class ScalarNodeParserTest extends TestCase
{
    private ScalarNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new ScalarNodeParser();
    }

    #[Test]
    public function itShouldSupportScalarOnly(): void
    {
        $nodes = iterator_to_array($this->parser->parse(Mutation::class, new ReflectionClass(Mutation::class), null));

        self::assertEmpty($nodes);
    }

    #[Test]
    public function itShouldGuardScalarImplementsScalarType(): void
    {
        self::expectException(ParseException::class);

        iterator_to_array($this->parser->parse(Scalar::class, new ReflectionClass(TestInvalidScalarType::class), null));
    }

    #[Test]
    public function itShouldParseScalar(): void
    {
        $nodes = iterator_to_array($this->parser->parse(Scalar::class, new ReflectionClass(TestScalarType::class), null));

        self::assertEquals([new ScalarNode(
            TestScalarType::class,
            'TestScalar',
            null,
            DateTime::class,
        )], $nodes);
    }
}
