<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\NodeParser\Child;

use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNode;
use Jerowork\GraphqlAttributeSchema\Node\Child\FieldNodeType;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ParseException;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInvalidConnectionMethodType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInvalidConnectionPropertyType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestInvalidType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;
use ReflectionClass;
use DateTimeImmutable;

/**
 * @internal
 */
final class ClassFieldsNodeParserTest extends TestCase
{
    private ClassFieldsNodeParser $parser;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new ClassFieldsNodeParser(
            $typeReferenceDecider = new TypeReferenceDecider(),
            new MethodArgumentsNodeParser(
                new AutowireNodeParser(),
                new EdgeArgsNodeParser(),
                new ArgNodeParser($typeReferenceDecider),
            ),
        );
    }

    #[Test]
    public function itShouldGuardInvalidReturnType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid return type');

        $this->parser->parse(new ReflectionClass(TestInvalidType::class));
    }

    #[Test]
    public function itShouldGuardPropertyConnectionType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid property type for connection');

        $this->parser->parse(new ReflectionClass(TestInvalidConnectionPropertyType::class));
    }

    #[Test]
    public function itShouldGuardMethodConnectionReturnType(): void
    {
        self::expectException(ParseException::class);
        self::expectExceptionMessage('Invalid return type for connection');

        $this->parser->parse(new ReflectionClass(TestInvalidConnectionMethodType::class));
    }

    #[Test]
    public function itShouldParseFields(): void
    {
        $fields = $this->parser->parse(new ReflectionClass(TestType::class));

        self::assertEquals([
            new FieldNode(
                ScalarTypeReference::create('string')->setNullableValue(),
                'typeId',
                null,
                [],
                FieldNodeType::Property,
                null,
                'id',
                null,
            ),
            new FieldNode(
                ObjectTypeReference::create(DateTimeImmutable::class),
                'date',
                null,
                [],
                FieldNodeType::Property,
                null,
                'date',
                null,
            ),
            new FieldNode(
                ScalarTypeReference::create('string')->setNullableValue(),
                'flow',
                null,
                [],
                FieldNodeType::Method,
                'flow',
                null,
                null,
            ),
            new FieldNode(
                ScalarTypeReference::create('string'),
                'status',
                null,
                [],
                FieldNodeType::Method,
                'getStatus',
                null,
                null,
            ),
        ], $fields);
    }
}
