<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestInputType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\NodeParser\Class\InputTypeClassNodeParser;
use Override;
use ReflectionClass;

/**
 * @internal
 */
final class InputTypeClassNodeParserTest extends TestCase
{
    private InputTypeClassNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new InputTypeClassNodeParser(
            new ClassFieldNodesParser(
                new MethodArgumentNodesParser(
                    new AutowireNodeParser(),
                    new EdgeArgsNodeParser(),
                    new ArgNodeParser(),
                ),
            ),
        );
    }

    #[Test]
    public function itShouldSupportInputTypeOnly(): void
    {
        self::assertTrue($this->parser->supports(InputType::class));
        self::assertFalse($this->parser->supports(Mutation::class));
    }

    #[Test]
    public function itShouldParseInputType(): void
    {
        $node = $this->parser->parse(new ReflectionClass(TestInputType::class), null);

        self::assertEquals(new InputTypeNode(
            TestInputType::class,
            'TestInput',
            'Test Input',
            [],
        ), $node);
    }
}
