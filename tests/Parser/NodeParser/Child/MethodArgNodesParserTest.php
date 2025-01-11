<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
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
final class MethodArgNodesParserTest extends TestCase
{
    private MethodArgumentNodesParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new MethodArgumentNodesParser(
            new AutowireNodeParser(),
            new ArgNodeParser(),
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
                ObjectReference::create(DateTimeImmutable::class),
                'date',
                null,
                'date',
            ),
            new ArgNode(
                ScalarReference::create('string')->setNullableValue(),
                'mutationId',
                'Mutation ID',
                'id',
            ),
        ], $argNodes);
    }
}
