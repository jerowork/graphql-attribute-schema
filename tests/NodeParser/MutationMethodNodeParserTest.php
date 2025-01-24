<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\MutationMethodNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestInvalidMutationWithInvalidConnectionReturnType;
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
            $typeReferenceDecider = new TypeReferenceDecider(),
            new MethodArgumentsNodeParser(
                new AutowireNodeParser(),
                new EdgeArgsNodeParser(),
                new ArgNodeParser($typeReferenceDecider),
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
    public function itShouldGuardConnectionReturnType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid return type for connection');

        $class = new ReflectionClass(TestInvalidMutationWithInvalidConnectionReturnType::class);

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
            ],
            ScalarTypeReference::create('string'),
            'testMutation',
            null,
        ), $node);
    }
}
