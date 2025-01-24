<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;
use Jerowork\GraphqlAttributeSchema\Node\ScalarNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestInvalidScalarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Scalar\TestScalarType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\NodeParser\ScalarClassNodeParser;
use Override;
use ReflectionClass;
use DateTime;

/**
 * @internal
 */
final class ScalarClassNodeParserTest extends TestCase
{
    private ScalarClassNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new ScalarClassNodeParser();
    }

    #[Test]
    public function itShouldSupportScalarOnly(): void
    {
        self::assertTrue($this->parser->supports(Scalar::class));
        self::assertFalse($this->parser->supports(Mutation::class));
    }

    #[Test]
    public function itShouldGuardScalarImplementsScalarType(): void
    {
        self::expectException(ParseException::class);

        $this->parser->parse(new ReflectionClass(TestInvalidScalarType::class), null);
    }

    #[Test]
    public function itShouldParseScalar(): void
    {
        $node = $this->parser->parse(new ReflectionClass(TestScalarType::class), null);

        self::assertEquals(new ScalarNode(
            TestScalarType::class,
            'TestScalar',
            null,
            DateTime::class,
        ), $node);
    }
}
