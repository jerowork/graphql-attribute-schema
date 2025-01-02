# GraphQL Attribute Schema
Build your GraphQL Schema (for [webonyx/graphql-php](https://github.com/webonyx/graphql-php)) based on attributes.

**Note:** this library is still work in progress, and misses some valuable features (see [todo](docs/todo.md))

## Why this library?
[webonyx/graphql-php](https://github.com/webonyx/graphql-php) requires a `Schema` in order to create a GraphQL Server.
This schema configuration is based on (large) PHP arrays.

Wouldn't it be nice to have a library in between which can read your mutation, query and type classes instead, and create
that schema configuration for you? 

This is where *GraphQL Attribute Schema* comes into place. By adding attributes to your classes,
*GraphQL Attribute Schema* will create the schema configuration for you.

## Documentation
Documentation is available in the [docs](docs/index.md) directory.
