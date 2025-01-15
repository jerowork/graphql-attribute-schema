<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser;

use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgumentNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Class\CustomScalarClassNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Class\EnumClassNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Class\InputTypeClassNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Method\MutationMethodNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Method\QueryMethodNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Class\TypeClassNodeParser;
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
        $methodArgNodesParser = new MethodArgumentNodesParser(
            new AutowireNodeParser(),
            new EdgeArgsNodeParser(),
            new ArgNodeParser(),
        );
        $classFieldNodesParser = new ClassFieldNodesParser($methodArgNodesParser);

        return new Parser(
            new NativeFinder(),
            new RoaveReflector(),
            [
                new EnumClassNodeParser(),
                new InputTypeClassNodeParser($classFieldNodesParser),
                new TypeClassNodeParser($classFieldNodesParser, new CursorNodeParser()),
                new CustomScalarClassNodeParser(),
            ],
            [
                new MutationMethodNodeParser($methodArgNodesParser),
                new QueryMethodNodeParser($methodArgNodesParser),
            ],
            $customTypes,
        );
    }
}
