<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestInputType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\NodeParser\InputTypeNodeParser;
use Override;
use ReflectionClass;

/**
 * @internal
 */
final class InputTypeNodeParserTest extends TestCase
{
    private InputTypeNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new InputTypeNodeParser(
            new ClassFieldsNodeParser(
                $typeReferenceDecider = new TypeReferenceDecider(),
                new MethodArgumentsNodeParser(
                    new AutowireNodeParser(),
                    new EdgeArgsNodeParser(),
                    new ArgNodeParser($typeReferenceDecider),
                ),
            ),
        );
    }

    #[Test]
    public function itShouldSupportInputTypeOnly(): void
    {
        $nodes = iterator_to_array($this->parser->parse(Mutation::class, new ReflectionClass(TestInputType::class), null));

        self::assertEmpty($nodes);
    }

    #[Test]
    public function itShouldParseInputType(): void
    {
        $nodes = iterator_to_array($this->parser->parse(InputType::class, new ReflectionClass(TestInputType::class), null));

        self::assertEquals([new InputTypeNode(
            TestInputType::class,
            'TestInput',
            'Test Input',
            [],
        )], $nodes);
    }
}
