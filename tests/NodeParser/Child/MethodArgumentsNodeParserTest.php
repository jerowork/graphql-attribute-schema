<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestInvalidMutationWithInvalidMethodArgument;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;
use ReflectionClass;
use DateTimeImmutable;

/**
 * @internal
 */
final class MethodArgumentsNodeParserTest extends TestCase
{
    private MethodArgumentsNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new MethodArgumentsNodeParser(
            new AutowireNodeParser(),
            new EdgeArgsNodeParser(),
            new ArgNodeParser(new TypeReferenceDecider()),
        );
    }

    #[Test]
    public function itShouldGuardInvalidMethodArgType(): void
    {
        $class = new ReflectionClass(TestInvalidMutationWithInvalidMethodArgument::class);

        self::expectException(ParseException::class);

        $this->parser->parse($class->getMethod('__invoke'));
    }

    #[Test]
    public function itShouldParseArgs(): void
    {
        $class = new ReflectionClass(TestMutation::class);

        $argNodes = $this->parser->parse($class->getMethod('testMutation'));

        self::assertEquals([
            new ArgNode(
                ObjectTypeReference::create(DateTimeImmutable::class),
                'date',
                null,
                'date',
            ),
            new ArgNode(
                ScalarTypeReference::create('string')->setNullableValue(),
                'mutationId',
                'Mutation ID',
                'id',
            ),
        ], $argNodes);
    }
}
