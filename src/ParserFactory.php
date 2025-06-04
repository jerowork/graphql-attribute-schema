<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use Jerowork\GraphqlAttributeSchema\NodeParser\ChainedNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ArgNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\AutowireNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\ClassFieldsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\CursorNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\EdgeArgsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\Child\MethodArgumentsNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\EnumNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\InputTypeNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\InterfaceTypeNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\MutationNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\QueryNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\ScalarNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeNodeParser;
use Jerowork\GraphqlAttributeSchema\NodeParser\TypeReferenceDecider;
use Jerowork\GraphqlAttributeSchema\Type\DateTimeType;
use Jerowork\GraphqlAttributeSchema\Util\Finder\Native\NativeFinder;
use Jerowork\GraphqlAttributeSchema\Util\Reflector\Native\NativeReflector;

final readonly class ParserFactory
{
    /**
     * @param list<class-string> $customTypes
     */
    public function create(
        array $customTypes = [
            DateTimeType::class,
        ],
    ): Parser {
        $typeReferenceDecider = new TypeReferenceDecider();

        $methodArgNodesParser = new MethodArgumentsNodeParser(
            new AutowireNodeParser(),
            new EdgeArgsNodeParser(),
            new ArgNodeParser($typeReferenceDecider),
        );
        $classFieldNodesParser = new ClassFieldsNodeParser(
            $typeReferenceDecider,
            $methodArgNodesParser,
        );
        $cursorNodeParser = new CursorNodeParser($typeReferenceDecider);

        return new Parser(
            new NativeFinder(),
            new NativeReflector(),
            new ChainedNodeParser([
                new EnumNodeParser(),
                new InputTypeNodeParser($classFieldNodesParser),
                new TypeNodeParser($classFieldNodesParser, $cursorNodeParser),
                new InterfaceTypeNodeParser($classFieldNodesParser, $cursorNodeParser),
                new ScalarNodeParser(),
                new MutationNodeParser($typeReferenceDecider, $methodArgNodesParser),
                new QueryNodeParser($typeReferenceDecider, $methodArgNodesParser),
            ]),
            $customTypes,
        );
    }
}
