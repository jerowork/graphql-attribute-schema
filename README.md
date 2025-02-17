# GraphQL Attribute Schema

[![Build Status](https://scrutinizer-ci.com/g/jerowork/graphql-attribute-schema/badges/build.png?b=main)](https://github.com/jerowork/graphql-attribute-schema/actions)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jerowork/graphql-attribute-schema.svg?style=flat)](https://scrutinizer-ci.com/g/jerowork/graphql-attribute-schema/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jerowork/graphql-attribute-schema.svg?style=flat)](https://scrutinizer-ci.com/g/jerowork/graphql-attribute-schema)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%5E8.3-8892BF.svg?style=flat)](http://www.php.net)

Easily build your GraphQL schema for [webonyx/graphql-php](https://github.com/webonyx/graphql-php) using **PHP attributes** instead of large configuration arrays.

## Why use this library?

The [webonyx/graphql-php](https://github.com/webonyx/graphql-php) package requires a **schema** to run a GraphQL server. Normally, this schema is defined using large and complex PHP arrays, making it harder to manage and maintain.

Wouldn’t it be great if there was a **simpler, more structured way** to define your schema?

That’s exactly what **GraphQL Attribute Schema** does! 🚀

By adding attributes (`#[Mutation]`, `#[Query]`, `#[Type]`, etc.) directly to your classes, this library **automatically generates** the GraphQL schema for you; **cleaner, faster, and easier to maintain**.

## 📖 Documentation
Documentation is available at [jerowork.github.io/graphql-attribute-schema](https://jerowork.github.io/graphql-attribute-schema) or in the [docs](docs/index.md).
