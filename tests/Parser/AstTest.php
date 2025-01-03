<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestAnotherEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Override;

/**
 * @internal
 */
final class AstTest extends TestCase
{
    private Ast $ast;
    private EnumNode $enumNode1;
    private EnumNode $enumNode2;
    private TypeNode $typeNode;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->ast = new Ast(
            $this->enumNode1 = new EnumNode(
                Type::createObject(TestEnumType::class),
                'enum1',
                null,
                ['case1', 'case2'],
            ),
            $this->typeNode = new TypeNode(
                Type::createObject(TestType::class),
                'type',
                null,
                [],
            ),
            $this->enumNode2 =new EnumNode(
                Type::createObject(TestAnotherEnumType::class),
                'enum2',
                null,
                ['case3', 'case4'],
            ),
            new InputTypeNode(
                Type::createObject(TestInputType::class),
                'inputType',
                null,
                [],
            ),
        );
    }

    #[Test]
    public function itShouldGetNodesByType(): void
    {
        self::assertSame([$this->enumNode1, $this->enumNode2], $this->ast->getNodesByNodeType(EnumNode::class));
        self::assertSame([$this->typeNode], $this->ast->getNodesByNodeType(TypeNode::class));
    }

    #[Test]
    public function itShouldGetNodeByTypeId(): void
    {
        self::assertSame($this->typeNode, $this->ast->getNodeByType(Type::createObject(TestType::class)));
        self::assertSame($this->enumNode2, $this->ast->getNodeByType(Type::createObject(TestAnotherEnumType::class)));
    }
}
