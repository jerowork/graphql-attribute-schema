<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\QueryMethodNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestInvalidQueryWithInvalidConnectionReturnType;
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
            $typeReferenceDecider = new TypeReferenceDecider(),
            new MethodArgumentsNodeParser(
                new AutowireNodeParser(),
                new EdgeArgsNodeParser(),
                new ArgNodeParser($typeReferenceDecider),
            ),
        );
    }

    #[Test]
    public function itShouldSupportQueryOnly(): void
    {
        $class = new ReflectionClass(TestInvalidQueryWithInvalidReturnType::class);

        $nodes = iterator_to_array($this->parser->parse(InputType::class, $class, $class->getMethod('__invoke')));

        self::assertEmpty($nodes);
    }

    #[Test]
    public function itShouldGuardThatMethodHasValidReturnType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid return type');

        $class = new ReflectionClass(TestInvalidQueryWithInvalidReturnType::class);

        iterator_to_array($this->parser->parse(Query::class, $class, $class->getMethod('__invoke')));
    }

    #[Test]
    public function itShouldGuardConnectionReturnType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid return type for connection');

        $class = new ReflectionClass(TestInvalidQueryWithInvalidConnectionReturnType::class);

        iterator_to_array($this->parser->parse(Query::class, $class, $class->getMethod('__invoke')));
    }

    #[Test]
    public function itShouldParseQuery(): void
    {
        $class = new ReflectionClass(TestQuery::class);

        $nodes = iterator_to_array($this->parser->parse(Query::class, $class, $class->getMethod('__invoke')));

        self::assertEquals([new QueryNode(
            TestQuery::class,
            'testQuery',
            'Test query',
            [
                new ArgNode(
                    ObjectTypeReference::create(DateTimeImmutable::class),
                    'date',
                    null,
                    'date',
                ),
                new ArgNode(
                    ScalarTypeReference::create('string'),
                    'id',
                    null,
                    'id',
                ),
            ],
            ScalarTypeReference::create('string'),
            '__invoke',
            null,
        )], $nodes);
    }
}
