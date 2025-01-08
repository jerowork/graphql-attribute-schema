<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Parser;

use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\ClassFieldNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\Child\MethodArgNodesParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\EnumNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\InputTypeNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\MutationNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\QueryNodeParser;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\TypeNodeParser;
use Jerowork\GraphqlAttributeSchema\Util\Finder\Native\NativeFinder;
use Jerowork\GraphqlAttributeSchema\Util\Reflector\Roave\RoaveReflector;

final readonly class ParserFactory
{
    /**
     * @param class-string ...$customTypes
     */
    public static function create(string ...$customTypes): Parser
    {
        $methodArgNodesParser = new MethodArgNodesParser();
        $classFieldNodesParser = new ClassFieldNodesParser($methodArgNodesParser);

        return new Parser(
            new NativeFinder(),
            new RoaveReflector(),
            [
                new EnumNodeParser(),
                new InputTypeNodeParser($classFieldNodesParser),
                new TypeNodeParser($classFieldNodesParser),
                new MutationNodeParser($methodArgNodesParser),
                new QueryNodeParser($methodArgNodesParser),
            ],
            $customTypes,
        );
    }
}
