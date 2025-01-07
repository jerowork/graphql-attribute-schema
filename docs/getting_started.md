# Getting started

## Requirements
*GraphQL Attribute Schema* is a standalone library. However, it needs a couple of other libraries to work:

- The main GraphQL library [webonyx/graphql-php](https://github.com/webonyx/graphql-php)
- A [psr/container](https://github.com/php-fig/container) compatible container (PSR-11), e.g.:
    - [php-di/php-di](https://github.com/PHP-DI/PHP-DI)
    - [symfony/dependency-injection](https://github.com/symfony/dependency-injection), by default included in the Symfony framework 

## Installation
Install via composer:
```bash
composer require jerowork/graphql-attribute-schema
```

## Integration with webonyx/graphql-php
In order to create a `Schema` for webonyx/graphql-php, a `SchemaBuilder` is provided in this library.
This `SchemaBuilder` requires an 'Abstract Syntax Tree' (or AST), which can be created with the `Parser`.

Both `SchemaBuilder` and `Parser` can be created quickly with the provided factories:

```php
use GraphQL\Server\StandardServer;
use GraphQL\Server\ServerConfig;
use Jerowork\GraphqlAttributeSchema\Parser\ParserFactory;
use Jerowork\GraphqlAttributeSchema\SchemaBuilderFactory;

// PSR-11 compatible container of your choice
$container = new YourPsr11Container();

// 1. Create an AST based on your classes
$ast = ParserFactory::create()->parse(__DIR__ . '/Path/To/GraphQL');

// with $ast->toArray(), the AST is cacheable (see Cache section)

// 2. Create the schema configuration
$schema = SchemaBuilderFactory::create($container)->build($ast);

// 3. Add schema to e.g. webonyx StandardServer
$server = new StandardServer(ServerConfig::create([
    'schema' => $schema,
]));
```

*GraphQL Attribute Schema* does not create a GraphQL Server for you.
How to create a GraphQL Server, please check e.g. https://webonyx.github.io/graphql-php/executing-queries/#using-server

### Example GraphQL Server with Symfony

As a quick-start, a simple example of a GraphQL Server with Symfony 
(requiring [symfony/psr-http-message-bridge](https://github.com/symfony/psr-http-message-bridge) and a PSR-7 implementation, e.g. [guzzlehttp/psr7](https://github.com/guzzle/psr7)):

_Note: any error handling is absent to keep the example simple._
```php
use GraphQL\Server\StandardServer;
use GraphQL\Server\ServerConfig;
use Jerowork\GraphqlAttributeSchema\Parser\ParserFactory;
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
    ) {}

    #[Route('/graphql', name: 'graphql.server', methods: Request::METHOD_POST)]
    public function __invoke(Request $request): Response
    {
        $ast = ParserFactory::create()->parse(__DIR__ . '/Path/To/GraphQL');
        $schema = SchemaBuilderFactory::create($container)->build($ast);
        
        $server = new StandardServer(ServerConfig::create([
            'schema' => $schema,
        ]));
        
        $result = $server->executePsrRequest(
            $this->httpMessageFactory
                ->createRequest($request)
                ->withParsedBody(json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR)),
        );
        
        // Batch requests
        if (is_array($result)) {
            return new JsonResponse(array_map(fn($result) => $result->toArray(), $result));        
        }
        
        // Single request
        return new JsonResponse($result->toArray());
    }
}
```

## Caching
To save parsing time (involving reflection of all classes and attributes),
the AST is serializable. This makes the AST cacheable.

```php
use GraphQL\Server\StandardServer;
use GraphQL\Server\ServerConfig;
use Jerowork\GraphqlAttributeSchema\Parser\ParserFactory;
use Jerowork\GraphqlAttributeSchema\SchemaBuilderFactory;

// 1. Create an AST based on your classes
$ast = ParserFactory::create()->parse(__DIR__ . '/Path/To/GraphQL');

// Add to cache
$someCache->set('graphql-attribute-schema.ast', json_encode($ast->toArray(), JSON_THROW_ON_ERROR));

// ...

// Get from cache
$ast = Ast::fromArray(json_decode($someCache->get('graphql-attribute-schema.ast'), true, flags: JSON_THROW_ON_ERROR));

// 2. Create the schema configuration
$schema = SchemaBuilderFactory::create($container)->build($ast);
```