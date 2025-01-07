<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\MutationNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestInvalidMutationWithNoMethods;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;
use ReflectionClass;
use DateTimeImmutable;

/**
 * @internal
 */
final class MutationNodeParserTest extends TestCase
{
    private MutationNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new MutationNodeParser(
            new MethodArgNodesParser(),
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
        self::expectExceptionMessage('Missing method in class');

        $this->parser->parse(new ReflectionClass(TestInvalidMutationWithNoMethods::class));
    }

    #[Test]
    public function itShouldParseInputType(): void
    {
        $node = $this->parser->parse(new ReflectionClass(TestMutation::class));

        self::assertEquals(new MutationNode(
            TestMutation::class,
            'test',
            'Test mutation',
            [
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
            ],
            Type::createScalar('string'),
            '__invoke',
            null,
        ), $node);
    }
}
