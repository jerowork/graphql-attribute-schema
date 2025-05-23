# 🚀 Getting Started

## ✅ Requirements

*GraphQL Attribute Schema* is a **standalone** library, but it depends on a few essential packages:

- **GraphQL library**: [webonyx/graphql-php](https://github.com/webonyx/graphql-php)
- **PSR-11 compatible container**, such as:
  - [PHP-DI](https://github.com/PHP-DI/PHP-DI)
  - [Symfony Dependency Injection](https://github.com/symfony/dependency-injection) (included by default in Symfony)

## 📦 Installation

Install the package via Composer:

```bash
composer require jerowork/graphql-attribute-schema
```

## 🔧 Integrating with webonyx/graphql-php

To generate a **GraphQL Schema** for `webonyx/graphql-php`, this library provides a `SchemaBuilder`.  
It requires an **Abstract Syntax Tree (AST)**, which is created using the `Parser`.

You can quickly set up both using the provided factories:

```php
use GraphQL\Server\StandardServer;
use GraphQL\Server\ServerConfig;
use Jerowork\GraphqlAttributeSchema\ParserFactory;
use Jerowork\GraphqlAttributeSchema\SchemaBuilderFactory;

// 1. Set up your PSR-11 compatible container
$container = new YourPsr11Container();

// 2. Create an AST from your GraphQL classes
$ast = (new ParserFactory())->create()->parse(__DIR__ . '/Path/To/GraphQL');

// 3. Build the schema from the AST
$schema = (new SchemaBuilderFactory())->create($container)->build($ast);

// 4. Add the schema to Webonyx StandardServer
$server = new StandardServer(ServerConfig::create([
    'schema' => $schema,
]));
```

📌 *This library does not create a GraphQL server for you.*  

To learn how to set up a server, check the [webonyx documentation](https://webonyx.github.io/graphql-php/executing-queries/#using-server).

## ⚡ Example: GraphQL Server with Symfony

For a quick start, here’s an example of a **GraphQL server in Symfony**.  
(Requires [symfony/psr-http-message-bridge](https://github.com/symfony/psr-http-message-bridge) and a PSR-7 implementation like [guzzlehttp/psr7](https://github.com/guzzle/psr7).)

*This example omits error handling for simplicity.*

```php
use GraphQL\Error\DebugFlag;
use GraphQL\Server\StandardServer;
use GraphQL\Server\ServerConfig;
use Jerowork\GraphqlAttributeSchema\ParserFactory;
use Jerowork\GraphqlAttributeSchema\SchemaBuilderFactory;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class GraphQLServerController
{
    public function __construct(
        private ContainerInterface $container,
        private HttpMessageFactoryInterface $httpMessageFactory,
        private ParserFactory $parserFactory,
        private SchemaBuilderFactory $schemaBuilderFactory,
    ) {}

    #[Route('/graphql', name: 'graphql.server', methods: Request::METHOD_POST)]
    public function __invoke(Request $request): Response
    {
        // 1. Parse GraphQL schema
        $ast = $this->parserFactory->create()->parse(__DIR__ . '/Path/To/GraphQL');
        $schema = $this->schemaBuilderFactory->create($this->container)->build($ast);
        
        // 2. Create GraphQL server
        $server = new StandardServer(ServerConfig::create([
            'schema' => $schema,
        ]));
        
        // 3. Handle request
        $result = $server->executePsrRequest(
            $this->httpMessageFactory
                ->createRequest($request)
                ->withParsedBody(json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR))
        );

        // 4. Handle batch requests
        if (is_array($result)) {
            return new JsonResponse(array_map(fn($res) => $res->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE), $result));        
        }
        
        // 5. Return response
        return new JsonResponse($result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE));
    }
}
```

## 🏎️ Caching for Performance

Parsing GraphQL classes and attributes **can be expensive** because it involves reflection.  
To improve performance, **cache the AST** (Abstract Syntax Tree) using serialization.

### Storing AST in Cache

```php
use Jerowork\GraphqlAttributeSchema\ParserFactory;

// 1. Generate AST
$ast = (new ParserFactory())->create()->parse(__DIR__ . '/Path/To/GraphQL');

// 2. Store in cache
$someCache->set('graphql-attribute-schema.ast', json_encode($ast->toArray(), JSON_THROW_ON_ERROR));
```

### Retrieving AST from Cache

```php
use Jerowork\GraphqlAttributeSchema\SchemaBuilderFactory;
use Jerowork\GraphqlAttributeSchema\ParserFactory;
use Jerowork\GraphqlAttributeSchema\Ast;

// 1. Retrieve from cache
$astArray = json_decode($someCache->get('graphql-attribute-schema.ast'), true, flags: JSON_THROW_ON_ERROR);
$ast = Ast::fromArray($astArray);

// 2. Build schema
$schema = (new SchemaBuilderFactory())->create($container)->build($ast);
```
