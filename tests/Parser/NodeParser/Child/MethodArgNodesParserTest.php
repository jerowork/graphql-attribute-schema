<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TesInvalidMutationWithInvalidMethodArgument;
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
    private MethodArgNodesParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new MethodArgNodesParser();
    }

    #[Test]
    public function itShouldGuardInvalidMethodArgType(): void
    {
        $class = new ReflectionClass(TesInvalidMutationWithInvalidMethodArgument::class);

        self::expectException(ParseException::class);

        $this->parser->parse($class->getMethod('__invoke'));
    }

    #[Test]
    public function itShouldParseArgs(): void
    {
        $class = new ReflectionClass(TestMutation::class);

        $argNodes = $this->parser->parse($class->getMethod('__invoke'));

        self::assertEquals([
            new ArgNode(
                Type::createObject(DateTimeImmutable::class),
                'date',
                null,
                'date',
            ),
            new ArgNode(
                Type::createScalar('string')->setNullableValue(),
                'mutationId',
                'Mutation ID',
                'id',
            ),
        ], $argNodes);
    }
}
