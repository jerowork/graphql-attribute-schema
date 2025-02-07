<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$reference of class Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgNode constructor expects Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference, Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\ArraySerializable given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Node/Child/ArgNode.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\FieldNode\\:\\:toArray\\(\\) should return array\\{reference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>, payload\\: array\\<string, mixed\\>\\}, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>, payload\\: array\\{reference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>, payload\\: array\\<string, mixed\\>\\}, name\\: string, description\\: string\\|null, propertyName\\: string\\}\\|array\\{service\\?\\: string, propertyName\\: string\\}\\}\\>, fieldType\\: string, methodName\\: string\\|null, propertyName\\: string\\|null, deprecationReason\\: string\\|null\\} but returns array\\{reference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}\\>, fieldType\\: \'method\'\\|\'property\', methodName\\: string\\|null, propertyName\\: string\\|null, deprecationReason\\: string\\|null\\}\\.$#',
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
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\MutationNode\\:\\:toArray\\(\\) should return array\\{className\\: class\\-string, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>, payload\\: array\\{propertyName\\: string\\}\\|array\\{reference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>, payload\\: array\\<string, mixed\\>\\}, name\\: string, description\\: string\\|null, propertyName\\: string\\}\\}\\>, outputReference\\: array\\{type\\: class\\-string, payload\\: array\\<string, mixed\\>\\}, methodName\\: string, deprecationReason\\: string\\|null\\} but returns array\\{className\\: class\\-string, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}\\>, outputReference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}, methodName\\: string, deprecationReason\\: string\\|null\\}\\.$#',
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
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\QueryNode\\:\\:toArray\\(\\) should return array\\{className\\: class\\-string, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>, payload\\: array\\{propertyName\\: string\\}\\|array\\{reference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>, payload\\: array\\<string, mixed\\>\\}, name\\: string, description\\: string\\|null, propertyName\\: string\\}\\}\\>, outputReference\\: array\\{type\\: class\\-string, payload\\: array\\<string, mixed\\>\\}, methodName\\: string, deprecationReason\\: string\\|null\\} but returns array\\{className\\: class\\-string, name\\: string, description\\: string\\|null, argumentNodes\\: list\\<array\\{node\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\Child\\\\ArgumentNode\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}\\>, outputReference\\: array\\{type\\: class\\-string\\<Jerowork\\\\GraphqlAttributeSchema\\\\Node\\\\TypeReference\\\\TypeReference\\>&literal\\-string, payload\\: array\\<string, mixed\\>\\}, methodName\\: string, deprecationReason\\: string\\|null\\}\\.$#',
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
	'message' => '#^Parameter \\#1 \\$config of class GraphQL\\\\Type\\\\Definition\\\\CustomScalarType constructor expects non\\-empty\\-array\\{name\\?\\: string\\|null, description\\?\\: string\\|null, serialize\\?\\: callable\\(mixed\\)\\: mixed, parseValue\\?\\: callable\\(mixed\\)\\: mixed, parseLiteral\\?\\: callable\\(GraphQL\\\\Language\\\\AST\\\\Node&GraphQL\\\\Language\\\\AST\\\\ValueNode, array\\<string, mixed\\>\\|null\\)\\: mixed, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\ScalarTypeDefinitionNode\\|null, extensionASTNodes\\?\\: array\\<GraphQL\\\\Language\\\\AST\\\\ScalarTypeExtensionNode\\>\\|null\\}, array\\{name\\: string, serialize\\: Closure\\(mixed\\)\\: string, parseValue\\: Closure\\(string\\)\\: mixed, parseLiteral\\: Closure\\(GraphQL\\\\Language\\\\AST\\\\StringValueNode\\)\\: mixed, description\\: string\\|null\\} given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Resolver/Type/CustomScalarTypeResolver.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$config of class GraphQL\\\\Type\\\\Definition\\\\InputObjectType constructor expects array\\{name\\?\\: string\\|null, description\\?\\: string\\|null, fields\\: \\(callable\\(\\)\\: iterable\\<array\\{name\\?\\: string, type\\: callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\), defaultValue\\?\\: mixed, description\\?\\: string\\|null, deprecationReason\\?\\: string\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InputValueDefinitionNode\\|null\\}\\|callable\\(\\)\\: \\(array\\{name\\?\\: string, type\\: callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\), defaultValue\\?\\: mixed, description\\?\\: string\\|null, deprecationReason\\?\\: string\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InputValueDefinitionNode\\|null\\}\\|GraphQL\\\\Type\\\\Definition\\\\InputObjectField\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\)\\|GraphQL\\\\Type\\\\Definition\\\\InputObjectField\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\>\\)\\|iterable\\<array\\{name\\?\\: string, type\\: \\(callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\)\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\), defaultValue\\?\\: mixed, description\\?\\: string\\|null, deprecationReason\\?\\: string\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InputValueDefinitionNode\\|null\\}\\|\\(callable\\(\\)\\: \\(array\\{name\\?\\: string, type\\: callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\), defaultValue\\?\\: mixed, description\\?\\: string\\|null, deprecationReason\\?\\: string\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InputValueDefinitionNode\\|null\\}\\|GraphQL\\\\Type\\\\Definition\\\\InputObjectField\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\)\\)\\|GraphQL\\\\Type\\\\Definition\\\\InputObjectField\\|\\(GraphQL\\\\Type\\\\Definition\\\\InputType&GraphQL\\\\Type\\\\Definition\\\\Type\\)\\>, parseValue\\?\\: callable\\(array\\<string, mixed\\>\\)\\: mixed, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InputObjectTypeDefinitionNode\\|null, extensionASTNodes\\?\\: array\\<GraphQL\\\\Language\\\\AST\\\\InputObjectTypeExtensionNode\\>\\|null\\}, array\\{name\\: string, description\\: string\\|null, fields\\: list\\<array\\{name\\: string, description\\: string\\|null, type\\: GraphQL\\\\Type\\\\Definition\\\\Type, args\\: list\\<array\\{name\\: string, description\\: string\\|null, type\\: GraphQL\\\\Type\\\\Definition\\\\Type\\}\\>\\}\\>\\} given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Resolver/Type/InputObjectTypeResolver.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$config of class GraphQL\\\\Type\\\\Definition\\\\InterfaceType constructor expects array\\{name\\?\\: string\\|null, description\\?\\: string\\|null, fields\\: \\(callable\\(\\)\\: iterable\\)\\|iterable, interfaces\\?\\: \\(callable\\(\\)\\: iterable\\<callable\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\|GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\>\\)\\|iterable\\<\\(callable\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\)\\|GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\>, resolveType\\?\\: \\(callable\\(mixed, mixed, GraphQL\\\\Type\\\\Definition\\\\ResolveInfo\\)\\: \\(callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|string\\|null\\)\\|GraphQL\\\\Deferred\\|GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|string\\|null\\)\\)\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InterfaceTypeDefinitionNode\\|null, extensionASTNodes\\?\\: array\\<GraphQL\\\\Language\\\\AST\\\\InterfaceTypeExtensionNode\\>\\|null\\}, array\\{name\\: string, description\\: string\\|null, fields\\: list\\<array\\{name\\: string, description\\: string\\|null, type\\: GraphQL\\\\Type\\\\Definition\\\\Type, args\\: list\\<array\\{name\\: string, description\\: string\\|null, type\\: GraphQL\\\\Type\\\\Definition\\\\Type\\}\\>, resolve\\: Closure, deprecationReason\\?\\: string\\}\\>, resolveType\\: Closure\\(object\\)\\: GraphQL\\\\Type\\\\Definition\\\\Type\\} given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Resolver/Type/InterfaceTypeResolver.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Jerowork\\\\GraphqlAttributeSchema\\\\Test\\\\Doubles\\\\FullFeatured\\\\Query\\\\DeprecatedQuery\\:\\:doSomeWork\\(\\) never returns string so it can be removed from the return type\\.$#',
	'identifier' => 'return.unusedType',
	'count' => 1,
	'path' => __DIR__ . '/tests/Doubles/FullFeatured/Query/DeprecatedQuery.php',
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
	'message' => '#^Parameter \\#1 \\$config of class GraphQL\\\\Type\\\\Definition\\\\InterfaceType constructor expects array\\{name\\?\\: string\\|null, description\\?\\: string\\|null, fields\\: \\(callable\\(\\)\\: iterable\\)\\|iterable, interfaces\\?\\: \\(callable\\(\\)\\: iterable\\<callable\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\|GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\>\\)\\|iterable\\<\\(callable\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\)\\|GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\>, resolveType\\?\\: \\(callable\\(mixed, mixed, GraphQL\\\\Type\\\\Definition\\\\ResolveInfo\\)\\: \\(callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|string\\|null\\)\\|GraphQL\\\\Deferred\\|GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|string\\|null\\)\\)\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InterfaceTypeDefinitionNode\\|null, extensionASTNodes\\?\\: array\\<GraphQL\\\\Language\\\\AST\\\\InterfaceTypeExtensionNode\\>\\|null\\}, array\\{name\\: \'name\', description\\: null, fields\\: array\\{array\\{name\\: \'id\', description\\: null, type\\: GraphQL\\\\Type\\\\Definition\\\\ScalarType, args\\: array\\{\\}, resolve\\: Closure\\(\\)\\: true\\}\\}, resolveType\\: Closure\\(\\)\\: true\\} given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/tests/Resolver/Type/InterfaceTypeResolverTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$config of class GraphQL\\\\Type\\\\Definition\\\\InterfaceType constructor expects array\\{name\\?\\: string\\|null, description\\?\\: string\\|null, fields\\: \\(callable\\(\\)\\: iterable\\)\\|iterable, interfaces\\?\\: \\(callable\\(\\)\\: iterable\\<callable\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\|GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\>\\)\\|iterable\\<\\(callable\\(\\)\\: GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\)\\|GraphQL\\\\Type\\\\Definition\\\\InterfaceType\\>, resolveType\\?\\: \\(callable\\(mixed, mixed, GraphQL\\\\Type\\\\Definition\\\\ResolveInfo\\)\\: \\(callable\\(\\)\\: \\(GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|string\\|null\\)\\|GraphQL\\\\Deferred\\|GraphQL\\\\Type\\\\Definition\\\\ObjectType\\|string\\|null\\)\\)\\|null, astNode\\?\\: GraphQL\\\\Language\\\\AST\\\\InterfaceTypeDefinitionNode\\|null, extensionASTNodes\\?\\: array\\<GraphQL\\\\Language\\\\AST\\\\InterfaceTypeExtensionNode\\>\\|null\\}, array\\{name\\: \'User\', description\\: null, fields\\: array\\{array\\{name\\: \'userId\', type\\: GraphQL\\\\Type\\\\Definition\\\\NonNull, description\\: null, args\\: array\\{\\}, resolve\\: Closure\\(\\)\\: true\\}\\}, resolveType\\: Closure\\(\\)\\: true\\} given\\.$#',
	'identifier' => 'argument.type',
	'count' => 5,
	'path' => __DIR__ . '/tests/SchemaBuilderTest.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
