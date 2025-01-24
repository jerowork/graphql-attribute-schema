<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use DateTime;
use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Node\Class\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\Method\MutationNode;
use Jerowork\GraphqlAttributeSchema\Node\Method\QueryNode;
use Jerowork\GraphqlAttributeSchema\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Parser;
use Jerowork\GraphqlAttributeSchema\ParserFactory;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Mutation\FoobarMutation;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Query\FoobarQuery;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarStatusType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\FoobarType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input\Baz;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Input\MutateFoobarInputType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\FullFeatured\Type\Scalar\TestScalarType;
use Jerowork\GraphqlAttributeSchema\Type\DateTimeType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;

/**
 * @internal
 */
final class ParserTest extends TestCase
{
    private Parser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = ParserFactory::create();
    }

    #[Test]
    public function itShouldParseDirectory(): void
    {
        $ast = $this->parser->parse(__DIR__ . '/Doubles/FullFeatured');

        $nodes = $ast->getNodesByNodeType(MutationNode::class);
        usort($nodes, fn(MutationNode $a, MutationNode $b) => $a->name <=> $b->name);
        self::assertEquals([
            new MutationNode(
                FoobarMutation::class,
                'first',
                'Mutate a foobar',
                [
                    new ArgNode(
                        ObjectTypeReference::create(MutateFoobarInputType::class),
                        'input',
                        null,
                        'input',
                    ),
                ],
                ObjectTypeReference::create(FoobarType::class),
                '__invoke',
                null,
            ),
            new MutationNode(
                FoobarMutation::class,
                'second',
                'Mutate a second foobar',
                [
                    new ArgNode(
                        ScalarTypeReference::create('string'),
                        'value',
                        null,
                        'value',
                    ),
                ],
                ScalarTypeReference::create('string'),
                'second',
                null,
            ),
        ], $nodes);

        self::assertEquals([
            new QueryNode(
                FoobarQuery::class,
                'getFoobar',
                'Get a Foobar',
                [
                    new ArgNode(
                        ScalarTypeReference::create('int')->setNullableValue(),
                        'id',
                        null,
                        'id',
                    ),
                    new ArgNode(
                        ObjectTypeReference::create(DateTimeImmutable::class),
                        'date',
                        null,
                        'date',
                    ),
                    new ArgNode(
                        ScalarTypeReference::create('bool')->setList(),
                        'values',
                        'List of values',
                        'values',
                    ),
                ],
                ScalarTypeReference::create('string')->setList(),
                '__invoke',
                null,
            ),
        ], $ast->getNodesByNodeType(QueryNode::class));

        $nodes = $ast->getNodesByNodeType(InputTypeNode::class);
        usort($nodes, fn(InputTypeNode $a, InputTypeNode $b) => $a->name <=> $b->name);
        self::assertEquals([
            new InputTypeNode(
                Baz::class,
                'Baz',
                null,
                [
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'bazId',
                        'A baz ID',
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(FoobarStatusType::class),
                        'status',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'status',
                        null,
                    ),
                ],
            ),
            new InputTypeNode(
                MutateFoobarInputType::class,
                'MutateFoobar',
                null,
                [
                    new FieldNode(
                        ScalarTypeReference::create('int'),
                        'id',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                    ),
                    new FieldNode(
                        ScalarTypeReference::create('string')->setNullableValue(),
                        'value',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'value',
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(Baz::class),
                        'baz',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'baz',
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(DateTimeImmutable::class)->setNullableValue(),
                        'date',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'date',
                        null,
                    ),
                ],
            ),
        ], $nodes);

        self::assertEquals([
            new TypeNode(
                FoobarType::class,
                'Foobar',
                'A foobar',
                [
                    new FieldNode(
                        ScalarTypeReference::create('string'),
                        'foobarId',
                        'A foobar ID',
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(FoobarStatusType::class)->setNullableValue(),
                        'status',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'status',
                        null,
                    ),
                    new FieldNode(
                        ObjectTypeReference::create(DateTimeImmutable::class)->setNullableValue(),
                        'date',
                        'A foobar date',
                        [
                            new ArgNode(
                                ScalarTypeReference::create('string'),
                                'limiting',
                                null,
                                'limit',
                            ),
                            new ArgNode(
                                ScalarTypeReference::create('int')->setNullableValue(),
                                'value',
                                'The value',
                                'value',
                            ),
                        ],
                        FieldNodeType::Method,
                        'getDate',
                        null,
                        null,
                    ),
                ],
                null,
            ),
        ], $ast->getNodesByNodeType(TypeNode::class));

        self::assertEquals([
            new EnumNode(
                FoobarStatusType::class,
                'FoobarStatus',
                'Foobar status',
                [
                    new EnumValueNode('open', null, null),
                    new EnumValueNode('closed', null, null),
                ],
            ),
        ], $ast->getNodesByNodeType(EnumNode::class));

        $nodes = $ast->getNodesByNodeType(CustomScalarNode::class);
        usort($nodes, fn(CustomScalarNode $a, CustomScalarNode $b) => $a->name <=> $b->name);
        self::assertEquals([
            new CustomScalarNode(
                DateTimeType::class,
                'DateTime',
                'Date and time (ISO-8601)',
                DateTimeImmutable::class,
            ),
            new CustomScalarNode(
                TestScalarType::class,
                'TestScalar',
                null,
                DateTime::class,
            ),
        ], $nodes);
    }
}
