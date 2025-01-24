<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\CustomScalarClassNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\EnumClassNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\InputTypeClassNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\MutationMethodNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\QueryMethodNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeClassNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Type\DateTimeType;
use Jerowork\GraphqlAttributeSchema\Util\Finder\Native\NativeFinder;
use Jerowork\GraphqlAttributeSchema\Util\Reflector\Roave\RoaveReflector;

final readonly class ParserFactory
{
    /**
     * @param list<class-string> $customTypes
     */
    public static function create(
        array $customTypes = [
            DateTimeType::class,
        ],
    ): Parser {
        $typeReferenceDecider = new TypeReferenceDecider();

        $methodArgNodesParser = new MethodArgumentNodesParser(
            new AutowireNodeParser(),
            new EdgeArgsNodeParser(),
            new ArgNodeParser($typeReferenceDecider),
        );
        $classFieldNodesParser = new ClassFieldNodesParser(
            $typeReferenceDecider,
            $methodArgNodesParser,
        );

        return new Parser(
            new NativeFinder(),
            new RoaveReflector(),
            [
                new EnumClassNodeParser(),
                new InputTypeClassNodeParser($classFieldNodesParser),
                new TypeClassNodeParser($classFieldNodesParser, new CursorNodeParser($typeReferenceDecider)),
                new CustomScalarClassNodeParser(),
                new MutationMethodNodeParser($typeReferenceDecider, $methodArgNodesParser),
                new QueryMethodNodeParser($typeReferenceDecider, $methodArgNodesParser),
            ],
            $customTypes,
        );
    }
}
