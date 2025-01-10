<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser\Method;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Method\MutationMethodNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestInvalidMutationWithInvalidReturnType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;
use ReflectionClass;
use DateTimeImmutable;

/**
 * @internal
 */
final class MutationMethodNodeParserTest extends TestCase
{
    private MutationMethodNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new MutationMethodNodeParser(
            new MethodArgumentNodesParser(
                new AutowireNodeParser(),
                new ArgNodeParser(),
            ),
        );
    }

    #[Test]
    public function itShouldSupportMutationOnly(): void
    {
        self::assertTrue($this->parser->supports(Mutation::class));
        self::assertFalse($this->parser->supports(InputType::class));
    }

    #[Test]
    public function itShouldGuardThatMethodHasValidReturnType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid return type');

        $class = new ReflectionClass(TestInvalidMutationWithInvalidReturnType::class);

        $this->parser->parse($class, $class->getMethod('mutation'));
    }

    #[Test]
    public function itShouldParseInputType(): void
    {
        $class = new ReflectionClass(TestMutation::class);

        $node = $this->parser->parse($class, $class->getMethod('testMutation'));

        self::assertEquals(new MutationNode(
            TestMutation::class,
            'testMutation',
            'Test mutation',
            [
                new ArgNode(
                    ObjectNodeType::create(DateTimeImmutable::class),
                    'date',
                    null,
                    'date',
                ),
                new ArgNode(
                    ScalarNodeType::create('string')->setNullableValue(),
                    'mutationId',
                    'Mutation ID',
                    'id',
                ),
            ],
            ScalarNodeType::create('string'),
            'testMutation',
            null,
        ), $node);
    }
}
