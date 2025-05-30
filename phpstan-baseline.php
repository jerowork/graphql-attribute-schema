<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$reference of class Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgNode constructor expects Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference, Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\ArraySerializable given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Node/Child/ArgNode.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\FieldNode\\:\\:toArray\\(\\) should return array\\{reference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>, payload\\: array\\<string, mixed\\>\\}, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>, payload\\: array\\{reference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>, payload\\: array\\<string, mixed\\>\\}, name\\: string, description\\: string\\|null, propertyName\\: string\\}\\|array\\{service\\?\\: string, propertyName\\: string\\}\\}\\>, fieldType\\: string, methodName\\: string\\|null, propertyName\\: string\\|null, deprecationReason\\: string\\|null, \\.\\.\\.\\} but returns array\\{reference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}\\>, fieldType\\: \'method\'\\|\'property\', methodName\\: string\\|null, propertyName\\: string\\|null, deprecationReason\\: string\\|null, \\.\\.\\.\\}\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Node/Child/FieldNode.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$reference of class Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\FieldNode constructor expects Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference, Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\ArraySerializable given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Node/Child/FieldNode.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\MutationNode\\:\\:toArray\\(\\) should return array\\{className\\: class\\-string, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>, payload\\: array\\{propertyName\\: string\\}\\|array\\{reference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>, payload\\: array\\<string, mixed\\>\\}, name\\: string, description\\: string\\|null, propertyName\\: string\\}\\}\\>, outputReference\\: array\\{type\\: class\\-string, payload\\: array\\<string, mixed\\>\\}, methodName\\: string, deprecationReason\\: string\\|null, deferredTypeLoader\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Type\\\\Loader\\\\DeferredTypeLoader\\>\\|null\\} but returns array\\{className\\: class\\-string, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}\\>, outputReference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}, methodName\\: string, deprecationReason\\: string\\|null, deferredTypeLoader\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Type\\\\Loader\\\\DeferredTypeLoader\\>\\|null\\}\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Node/MutationNode.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#5 \\$outputReference of class Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\MutationNode constructor expects Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference, Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\ArraySerializable given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Node/MutationNode.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\QueryNode\\:\\:toArray\\(\\) should return array\\{className\\: class\\-string, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>, payload\\: array\\{propertyName\\: string\\}\\|array\\{reference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>, payload\\: array\\<string, mixed\\>\\}, name\\: string, description\\: string\\|null, propertyName\\: string\\}\\}\\>, outputReference\\: array\\{type\\: class\\-string, payload\\: array\\<string, mixed\\>\\}, methodName\\: string, deprecationReason\\: string\\|null, deferredTypeLoader\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Type\\\\Loader\\\\DeferredTypeLoader\\>\\|null\\} but returns array\\{className\\: class\\-string, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}\\>, outputReference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}, methodName\\: string, deprecationReason\\: string\\|null, deferredTypeLoader\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Type\\\\Loader\\\\DeferredTypeLoader\\>\\|null\\}\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Node/QueryNode.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#5 \\$outputReference of class Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\QueryNode constructor expects Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference, Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\ArraySerializable given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Node/QueryNode.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\ChainedNodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/ChainedNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\Child\\\\ArgNodeParser\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/Child/ArgNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\Child\\\\AutowireNodeParser\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/Child/AutowireNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\Child\\\\ClassFieldsNodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/Child/ClassFieldsNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\Child\\\\ClassFieldsNodeParser\\:\\:parseMethods\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/Child/ClassFieldsNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\Child\\\\ClassFieldsNodeParser\\:\\:parseProperties\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/Child/ClassFieldsNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\Child\\\\CursorNodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/Child/CursorNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\Child\\\\CursorNodeParser\\:\\:parseMethods\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/Child/CursorNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\Child\\\\CursorNodeParser\\:\\:parseProperties\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/Child/CursorNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\EnumNodeParser\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/EnumNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\EnumNodeParser\\:\\:getValues\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/EnumNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\EnumNodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/EnumNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\EnumNodeParser\\:\\:retrieveNameForType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/EnumNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\EnumNodeParser\\:\\:retrieveNameFromClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/EnumNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\InputTypeNodeParser\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/InputTypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\InputTypeNodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/InputTypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\InputTypeNodeParser\\:\\:retrieveNameForType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/InputTypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\InputTypeNodeParser\\:\\:retrieveNameFromClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/InputTypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\InterfaceTypeNodeParser\\:\\:addParentInterfaceType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/InterfaceTypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\InterfaceTypeNodeParser\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/InterfaceTypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\InterfaceTypeNodeParser\\:\\:getInterfaceTypes\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/InterfaceTypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\InterfaceTypeNodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/InterfaceTypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\InterfaceTypeNodeParser\\:\\:retrieveNameForType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/InterfaceTypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\InterfaceTypeNodeParser\\:\\:retrieveNameFromClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/InterfaceTypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\MutationNodeParser\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/MutationNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\MutationNodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/MutationNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\NodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/NodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\QueryNodeParser\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/QueryNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\QueryNodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/QueryNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\ScalarNodeParser\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/ScalarNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\ScalarNodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/ScalarNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\ScalarNodeParser\\:\\:retrieveNameForType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/ScalarNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\ScalarNodeParser\\:\\:retrieveNameFromClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/ScalarNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\TypeNodeParser\\:\\:addParentInterfaceType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/TypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\TypeNodeParser\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/TypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\TypeNodeParser\\:\\:getInterfaceTypes\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/TypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\TypeNodeParser\\:\\:parse\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/TypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\TypeNodeParser\\:\\:retrieveNameForType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/TypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\NodeParser\\\\TypeNodeParser\\:\\:retrieveNameFromClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/NodeParser/TypeNodeParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Parser\\:\\:getClasses\\(\\) return type with generic class ReflectionClass does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/Parser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Parser\\:\\:getSupportedAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/Parser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Parser\\:\\:parseClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/Parser.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Resolver\\\\RootTypeResolver\\:\\:createType\\(\\) should return array\\{name\\: string, description\\: string\\|null, type\\: Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type, args\\: list\\<array\\{name\\: string, description\\: string\\|null, type\\: Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type\\}\\>, resolve\\: Closure, deprecationReason\\?\\: string\\} but returns array\\{name\\: string, description\\: string\\|null, type\\: Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type, args\\: list\\<array\\{name\\: string, type\\: \\(Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type\\)\\|GraphQL\\\\Type\\\\Definition\\\\Type, description\\: string\\|null\\}\\>, resolve\\: Closure, deprecationReason\\?\\: string\\}\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Resolver/RootTypeResolver.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Resolver\\\\Type\\\\FieldResolver\\:\\:getFields\\(\\) should return list\\<array\\{name\\: string, description\\: string\\|null, type\\: Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type, args\\: list\\<array\\{name\\: string, description\\: string\\|null, type\\: Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type\\}\\>, resolve\\: Closure, deprecationReason\\?\\: string\\}\\> but returns list\\<array\\{name\\: string, description\\: string\\|null, type\\: Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type, args\\: list\\<array\\{name\\: string, type\\: \\(Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type\\)\\|GraphQL\\\\Type\\\\Definition\\\\Type, description\\: string\\|null\\}\\>, resolve\\: Closure, deprecationReason\\?\\: string\\}\\>\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Resolver/Type/FieldResolver.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$config of class GraphQL\\\\Type\\\\Definition\\\\InputObjectType constructor expects array\\{name\\?\\: string\\|null, description\\?\\: string\\|null, fields\\: \\(callable\\(\\)\\: iterable\\<array\\{name\\?\\: string, type\\: callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\), defaultValue\\?\\: mixed, description\\?\\: string\\|null, deprecationReason\\?\\: string\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InputValueDefinitionNode\\|null\\}\\|callable\\(\\)\\: \\(array\\{name\\?\\: string, type\\: callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\), defaultValue\\?\\: mixed, description\\?\\: string\\|null, deprecationReason\\?\\: string\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InputValueDefinitionNode\\|null\\}\\|GraphQL\\\\Type\\\\Definition\\\\InputObjectField\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\)\\|GraphQL\\\\Type\\\\Definition\\\\InputObjectField\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\>\\)\\|iterable\\<array\\{name\\?\\: string, type\\: \\(callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\)\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\), defaultValue\\?\\: mixed, description\\?\\: string\\|null, deprecationReason\\?\\: string\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InputValueDefinitionNode\\|null\\}\\|\\(callable\\(\\)\\: \\(array\\{name\\?\\: string, type\\: callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\), defaultValue\\?\\: mixed, description\\?\\: string\\|null, deprecationReason\\?\\: string\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InputValueDefinitionNode\\|null\\}\\|GraphQL\\\\Type\\\\Definition\\\\InputObjectField\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\)\\)\\|GraphQL\\\\Type\\\\Definition\\\\InputObjectField\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\>, parseValue\\?\\: callable\\(array\\<string, mixed\\>\\)\\: mixed, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InputObjectTypeDefinitionNode\\|null, extensionASTNodes\\?\\: array\\<GraphQL\\\\Language\\\\AST\\\\InputObjectTypeExtensionNode\\>\\|null\\}, array\\{name\\: string, description\\: string\\|null, fields\\: Closure\\(\\)\\: list\\<array\\{name\\: string, description\\: string\\|null, type\\: Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type, args\\: list\\<array\\{name\\: string, description\\: string\\|null, type\\: Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type\\}\\>\\}\\>\\} given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Resolver/Type/InputObjectTypeResolver.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$config of class GraphQL\\\\Type\\\\Definition\\\\InterfaceType constructor expects array\\{name\\?\\: string\\|null, description\\?\\: string\\|null, fields\\: \\(callable\\(\\)\\: iterable\\)\\|iterable, interfaces\\?\\: \\(callable\\(\\)\\: iterable\\<callable\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\|GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\>\\)\\|iterable\\<\\(callable\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\)\\|GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\>, resolveType\\?\\: \\(callable\\(mixed, mixed, GraphQL\\\\Type\\\\Definition\\\\ResolveInfo\\)\\: \\(callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|string\\|null\\)\\|GraphQL\\\\Deferred\\|GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|string\\|null\\)\\)\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InterfaceTypeDefinitionNode\\|null, extensionASTNodes\\?\\: array\\<GraphQL\\\\Language\\\\AST\\\\InterfaceTypeExtensionNode\\>\\|null\\}, array\\{name\\: string, description\\: string\\|null, fields\\: Closure\\(\\)\\: list\\<array\\{name\\: string, description\\: string\\|null, type\\: Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type, args\\: list\\<array\\{name\\: string, description\\: string\\|null, type\\: Closure\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type\\}\\>, resolve\\: Closure, deprecationReason\\?\\: string\\}\\>, resolveType\\: Closure\\(object\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type\\} given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Resolver/Type/InterfaceTypeResolver.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$config of class GraphQL\\\\Type\\\\Definition\\\\UnionType constructor expects array\\{name\\?\\: string\\|null, description\\?\\: string\\|null, types\\: \\(callable\\(\\)\\: iterable\\<callable\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|GraphQL\\\\Type\\\\Definition\\\\ObjectType\\>\\)\\|iterable\\<\\(callable\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\ObjectType\\)\\|GraphQL\\\\Type\\\\Definition\\\\ObjectType\\>, resolveType\\?\\: \\(callable\\(mixed, mixed, GraphQL\\\\Type\\\\Definition\\\\ResolveInfo\\)\\: \\(callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|string\\|null\\)\\|GraphQL\\\\Deferred\\|GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|string\\|null\\)\\)\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\UnionTypeDefinitionNode\\|null, extensionASTNodes\\?\\: array\\<GraphQL\\\\Language\\\\AST\\\\UnionTypeExtensionNode\\>\\|null\\}, array\\{name\\: string, types\\: list\\<GraphQL\\\\Type\\\\Definition\\\\Type\\>, resolveType\\: Closure\\(object\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type\\} given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Resolver/Type/UnionTypeResolver.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Util\\\\Reflector\\\\Reflector\\:\\:getClasses\\(\\) return type with generic class ReflectionClass does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/Util/Reflector/Reflector.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Util\\\\Reflector\\\\Roave\\\\RoaveReflector\\:\\:getClasses\\(\\) return type with generic class ReflectionClass does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/Util/Reflector/Roave/RoaveReflector.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\FullFeatured\\\\Query\\\\DeprecatedQuery\\:\\:doSomeWork\\(\\) never returns string so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/FullFeatured/Query/DeprecatedQuery.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\FullFeatured\\\\Query\\\\WithUnionOutputQuery\\:\\:getUnionQuery\\(\\) never returns Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\FullFeatured\\\\Type\\\\AgentType so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/FullFeatured/Query/WithUnionOutputQuery.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\FullFeatured\\\\Query\\\\WithUnionOutputQuery\\:\\:getUnionQuery\\(\\) never returns Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\FullFeatured\\\\Type\\\\FoobarType so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/FullFeatured/Query/WithUnionOutputQuery.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\FullFeatured\\\\Query\\\\WithUnionOutputQuery\\:\\:getUnionQuery\\(\\) should return Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\FullFeatured\\\\Type\\\\AgentType\\|Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\FullFeatured\\\\Type\\\\FoobarType but returns string\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/FullFeatured/Query/WithUnionOutputQuery.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\FullFeatured\\\\Type\\\\FoobarType\\:\\:getDate\\(\\) never returns null so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/FullFeatured/Type/FoobarType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Mutation\\\\TestInvalidMutationWithInvalidMethodArgument\\:\\:__invoke\\(\\) has parameter \\$id with no type specified\\.$#',
	'identifier' => 'missingType.parameter',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Mutation/TestInvalidMutationWithInvalidMethodArgument.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getBazs\\(\\) never returns Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestType so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getBazs\\(\\) never returns Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestTypeWithAutowire so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getBazs\\(\\) should return Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestType\\|Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestTypeWithAutowire but returns string\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getFoobars\\(\\) never returns Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestType so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getFoobars\\(\\) never returns Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestTypeWithAutowire so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getFoobars\\(\\) should return Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestType\\|Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestTypeWithAutowire but returns string\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getListOfQuxs\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getNullableBazs\\(\\) never returns Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestType so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getNullableBazs\\(\\) never returns Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestTypeWithAutowire so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getNullableBazs\\(\\) never returns null so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Query\\\\TestQueryWithUnionType\\:\\:getNullableBazs\\(\\) should return Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestType\\|Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestTypeWithAutowire\\|null but returns string\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Query/TestQueryWithUnionType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestCascadingInterfaceType\\:\\:getStatus\\(\\) never returns null so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Type/TestCascadingInterfaceType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestExtendsAbstractInterfaceType\\:\\:getStatus\\(\\) never returns null so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Type/TestExtendsAbstractInterfaceType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestExtendsInterfaceType\\:\\:cursor\\(\\) never returns null so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Type/TestExtendsInterfaceType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestType\\:\\:flow\\(\\) never returns null so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Type/TestType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestTypeWithAutowire\\:\\:invalidServiceWithoutCustomId\\(\\) has parameter \\$service with no type specified\\.$#',
	'identifier' => 'missingType.parameter',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Type/TestTypeWithAutowire.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\Type\\\\TestTypeWithAutowire\\:\\:serviceWithCustomId\\(\\) has parameter \\$service with no type specified\\.$#',
	'identifier' => 'missingType.parameter',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/Type/TestTypeWithAutowire.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/GetClassAttributeTraitTest\\.php\\:25\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/GetClassAttributeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/GetClassAttributeTraitTest\\.php\\:40\\:\\:getAttribute\\(\\) has parameter \\$reflector with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/GetClassAttributeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/GetInterfaceTypesTraitTest\\.php\\:24\\:\\:addParentInterfaceType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/GetInterfaceTypesTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/GetInterfaceTypesTraitTest\\.php\\:24\\:\\:getInterfaceTypes\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/GetInterfaceTypesTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/RetrieveNameForTypeTraitTest\\.php\\:26\\:\\:retrieveNameForType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/RetrieveNameForTypeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/RetrieveNameForTypeTraitTest\\.php\\:26\\:\\:retrieveNameFromClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/RetrieveNameForTypeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/RetrieveNameForTypeTraitTest\\.php\\:41\\:\\:retrieveNameForType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/RetrieveNameForTypeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/RetrieveNameForTypeTraitTest\\.php\\:41\\:\\:retrieveNameFromClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/RetrieveNameForTypeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/RetrieveNameForTypeTraitTest\\.php\\:54\\:\\:retrieveNameForType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/RetrieveNameForTypeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/RetrieveNameForTypeTraitTest\\.php\\:54\\:\\:retrieveNameFromClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/RetrieveNameForTypeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/RetrieveNameForTypeTraitTest\\.php\\:67\\:\\:retrieveNameForType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/RetrieveNameForTypeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/RetrieveNameForTypeTraitTest\\.php\\:67\\:\\:retrieveNameFromClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/RetrieveNameForTypeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/RetrieveNameForTypeTraitTest\\.php\\:80\\:\\:retrieveNameForType\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/RetrieveNameForTypeTraitTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method class@anonymous/tests/NodeParser/RetrieveNameForTypeTraitTest\\.php\\:80\\:\\:retrieveNameFromClass\\(\\) has parameter \\$class with generic class ReflectionClass but does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/tests/NodeParser/RetrieveNameForTypeTraitTest.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
