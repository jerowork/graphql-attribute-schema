<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\QueryNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestInvalidQueryWithNoMethods;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestQuery;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Override;

/**
 * @internal
 */
final class QueryNodeParserTest extends TestCase
{
    private QueryNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new QueryNodeParser(
            new MethodArgNodesParser(),
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
        self::expectExceptionMessage('Missing method in class');

        $this->parser->parse(new ReflectionClass(TestInvalidQueryWithNoMethods::class));
    }

    #[Test]
    public function itShouldParseInputType(): void
    {
        $node = $this->parser->parse(new ReflectionClass(TestQuery::class));

        self::assertEquals(new QueryNode(
            Type::createObject(TestQuery::class),
            'testQuery',
            'Test query',
            [
                new ArgNode(
                    Type::createObject(DateTimeImmutable::class),
                    'date',
                    null,
                    true,
                    'date',
                ),
                new ArgNode(
                    Type::createScalar('string'),
                    'id',
                    null,
                    true,
                    'id',
                ),
            ],
            Type::createScalar('string'),
            true,
            '__invoke',
        ), $node);
    }
}
