<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser;

use DateTime;
use DateTimeImmutable;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\ArgNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\EnumValueNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\MutationNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Method\QueryNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\CustomScalarNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Class\TypeNode;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ObjectReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\ScalarReference;
use Jerowork\GraphqlAttributeSchema\Parser\Parser;
use Jerowork\GraphqlAttributeSchema\Parser\ParserFactory;
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
    public function it_should_parse_directory(): void
    {
        $ast = $this->parser->parse(__DIR__ . '/../Doubles/FullFeatured');

        self::assertEquals([
            new MutationNode(
                FoobarMutation::class,
                'first',
                'Mutate a foobar',
                [
                    new ArgNode(
                        ObjectReference::create(MutateFoobarInputType::class),
                        'input',
                        null,
                        'input',
                    ),
                ],
                ObjectReference::create(FoobarType::class),
                '__invoke',
                null,
            ),
            new MutationNode(
                FoobarMutation::class,
                'second',
                'Mutate a second foobar',
                [
                    new ArgNode(
                        ScalarReference::create('string'),
                        'value',
                        null,
                        'value',
                    ),
                ],
                ScalarReference::create('string'),
                'second',
                null,
            ),
        ], $ast->getNodesByNodeType(MutationNode::class));

        self::assertEquals([
            new QueryNode(
                FoobarQuery::class,
                'getFoobar',
                'Get a Foobar',
                [
                    new ArgNode(
                        ScalarReference::create('int')->setNullableValue(),
                        'id',
                        null,
                        'id',
                    ),
                    new ArgNode(
                        ObjectReference::create(DateTimeImmutable::class),
                        'date',
                        null,
                        'date',
                    ),
                    new ArgNode(
                        ScalarReference::create('bool')->setList(),
                        'values',
                        'List of values',
                        'values',
                    ),
                ],
                ScalarReference::create('string')->setList(),
                '__invoke',
                null,
            ),
        ], $ast->getNodesByNodeType(QueryNode::class));

        self::assertEquals([
            new InputTypeNode(
                Baz::class,
                'Baz',
                null,
                [
                    new FieldNode(
                        ScalarReference::create('string'),
                        'bazId',
                        'A baz ID',
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                    ),
                    new FieldNode(
                        ObjectReference::create(FoobarStatusType::class),
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
                        ScalarReference::create('int'),
                        'id',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                    ),
                    new FieldNode(
                        ScalarReference::create('string')->setNullableValue(),
                        'value',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'value',
                        null,
                    ),
                    new FieldNode(
                        ObjectReference::create(Baz::class),
                        'baz',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'baz',
                        null,
                    ),
                    new FieldNode(
                        ObjectReference::create(DateTimeImmutable::class)->setNullableValue(),
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
        ], $ast->getNodesByNodeType(InputTypeNode::class));

        self::assertEquals([
            new TypeNode(
                FoobarType::class,
                'Foobar',
                'A foobar',
                [
                    new FieldNode(
                        ScalarReference::create('string'),
                        'foobarId',
                        'A foobar ID',
                        [],
                        FieldNodeType::Property,
                        null,
                        'id',
                        null,
                    ),
                    new FieldNode(
                        ObjectReference::create(FoobarStatusType::class)->setNullableValue(),
                        'status',
                        null,
                        [],
                        FieldNodeType::Property,
                        null,
                        'status',
                        null,
                    ),
                    new FieldNode(
                        ObjectReference::create(DateTimeImmutable::class)->setNullableValue(),
                        'date',
                        'A foobar date',
                        [
                            new ArgNode(
                                ScalarReference::create('string'),
                                'limiting',
                                null,
                                'limit',
                            ),
                            new ArgNode(
                                ScalarReference::create('int')->setNullableValue(),
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

        self::assertEquals([
            new CustomScalarNode(
                TestScalarType::class,
                'TestScalar',
                null,
                DateTime::class,
            ),
            new CustomScalarNode(
                DateTimeType::class,
                'DateTime',
                'Date and time (ISO-8601)',
                DateTimeImmutable::class,
            ),
        ], $ast->getNodesByNodeType(CustomScalarNode::class));
    }
}
