<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Node\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\InterfaceTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\MutationNode;
use Jerowork\GraphqlAttributeSchema\Node\QueryNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestAnotherEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Enum\TestEnumType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InputType\TestInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\TestInterfaceType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\InterfaceType\TestOtherInterfaceType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Query\TestQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\Loader\TestTypeLoader;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AstTest extends TestCase
{
    private Ast $ast;
    private EnumNode $enumNode1;
    private EnumNode $enumNode2;
    private TypeNode $typeNode;
    private InterfaceTypeNode $interfaceTypeNode;

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
                null,
                [
                    TestInterfaceType::class,
                ],
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
            $this->interfaceTypeNode = new InterfaceTypeNode(
                TestInterfaceType::class,
                'test',
                null,
                [],
                null,
                [
                    TestOtherInterfaceType::class,
                ],
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
    public function itShouldGetNodesImplementingAnInterface(): void
    {
        $nodes = $this->ast->getNodesImplementingInterface();

        self::assertSame([
            $this->typeNode,
            $this->interfaceTypeNode,
        ], $nodes);
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
                        ScalarTypeReference::create('int'),
                        'name',
                        'a description',
                        'aPropertyName',
                    ),
                    new ArgNode(
                        ScalarTypeReference::create('string'),
                        'name 2',
                        'b description',
                        'bPropertyName',
                    ),
                ],
                ObjectTypeReference::create(TestType::class),
                'method',
                'deprecated',
                null,
            ),
            new QueryNode(
                TestQuery::class,
                'name',
                'description',
                [
                    new ArgNode(
                        ScalarTypeReference::create('int'),
                        'name',
                        'a description',
                        'aPropertyName',
                    ),
                    new ArgNode(
                        ScalarTypeReference::create('string'),
                        'name 2',
                        'b description',
                        'bPropertyName',
                    ),
                ],
                ObjectTypeReference::create(TestType::class),
                'method',
                'deprecated',
                TestTypeLoader::class,
            ),
        );

        self::assertEquals(Ast::fromArray($ast->toArray()), $ast);
    }
}
