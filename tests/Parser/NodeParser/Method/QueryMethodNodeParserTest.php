<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser\Method;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\QueryNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Method\QueryMethodNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestInvalidQueryWithInvalidReturnType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestQuery;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Override;

/**
 * @internal
 */
final class QueryMethodNodeParserTest extends TestCase
{
    private QueryMethodNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new QueryMethodNodeParser(
            new MethodArgumentNodesParser(
                new AutowireNodeParser(),
                new ArgNodeParser(),
            ),
        );
    }

    #[Test]
    public function itShouldSupportQueryOnly(): void
    {
        self::assertTrue($this->parser->supports(Query::class));
        self::assertFalse($this->parser->supports(InputType::class));
    }

    #[Test]
    public function itShouldGuardThatMethodHasValidReturnType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid return type');

        $class = new ReflectionClass(TestInvalidQueryWithInvalidReturnType::class);

        $this->parser->parse($class, $class->getMethod('__invoke'));
    }

    #[Test]
    public function itShouldParseInputType(): void
    {
        $class = new ReflectionClass(TestQuery::class);

        $node = $this->parser->parse($class, $class->getMethod('__invoke'));

        self::assertEquals(new QueryNode(
            TestQuery::class,
            'testQuery',
            'Test query',
            [
                new ArgNode(
                    Type::createObject(DateTimeImmutable::class),
                    'date',
                    null,
                    'date',
                ),
                new ArgNode(
                    Type::createScalar('string'),
                    'id',
                    null,
                    'id',
                ),
            ],
            Type::createScalar('string'),
            '__invoke',
            null,
        ), $node);
    }
}
