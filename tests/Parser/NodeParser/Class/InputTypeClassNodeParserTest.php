<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser\Class;

use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestInputType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Class\InputTypeClassNodeParser;
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
        $node = $this->parser->parse(new ReflectionClass(TestInputType::class));

        self::assertEquals(new InputTypeNode(
            TestInputType::class,
            'TestInput',
            'Test Input',
            [],
        ), $node);
    }
}
