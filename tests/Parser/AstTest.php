<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser;

use Jerowork\GraphqlAttributeSchema\Parser\Ast;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\QueryNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ObjectNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Type\ScalarNodeType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestAnotherEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestQuery;
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
                TestEnumType::class,
                'enum1',
                null,
                [
                    new EnumValueNode('case1', null, null),
                    new EnumValueNode('case2', null, null),
                ],
            ),
            $this->typeNode = new TypeNode(
                TestType::class,
                'type',
                null,
                [],
            ),
            $this->enumNode2 =new EnumNode(
                TestAnotherEnumType::class,
                'enum2',
                null,
                [
                    new EnumValueNode('case3', null, null),
                    new EnumValueNode('case4', null, null),
                ],
            ),
            new InputTypeNode(
                TestInputType::class,
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
        self::assertSame($this->typeNode, $this->ast->getNodeByClassName(TestType::class));
        self::assertSame($this->enumNode2, $this->ast->getNodeByClassName(TestAnotherEnumType::class));
    }

    #[Test]
    public function itShouldSerializeAndDeserialize(): void
    {
        $ast = new Ast(
            new MutationNode(
                TestMutation::class,
                'name',
                'description',
                [
                    new ArgNode(
                        ScalarNodeType::create('int'),
                        'name',
                        'a description',
                        'aPropertyName',
                    ),
                    new ArgNode(
                        ScalarNodeType::create('string'),
                        'name 2',
                        'b description',
                        'bPropertyName',
                    ),
                ],
                ObjectNodeType::create(TestType::class),
                'method',
                'deprecated',
            ),
            new QueryNode(
                TestQuery::class,
                'name',
                'description',
                [
                    new ArgNode(
                        ScalarNodeType::create('int'),
                        'name',
                        'a description',
                        'aPropertyName',
                    ),
                    new ArgNode(
                        ScalarNodeType::create('string'),
                        'name 2',
                        'b description',
                        'bPropertyName',
                    ),
                ],
                ObjectNodeType::create(TestType::class),
                'method',
                'deprecated',
            ),
        );

        self::assertEquals(Ast::fromArray($ast->toArray()), $ast);
    }
}
