# GraphQL Attribute Schema

[![Build Status](https://scrutinizer-ci.com/g/jerowork/graphql-attribute-schema/badges/build.png?b=main)](https://github.com/jerowork/graphql-attribute-schema/actions)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jerowork/graphql-attribute-schema.svg?style=flat)](https://scrutinizer-ci.com/g/jerowork/graphql-attribute-schema/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jerowork/graphql-attribute-schema.svg?style=flat)](https://scrutinizer-ci.com/g/jerowork/graphql-attribute-schema)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)
[![Downloads](https://img.shields.io/packagist/dt/jerowork/graphql-attribute-schema?style=flat&color=4bc61d)](https://packagist.org/packages/jerowork/graphql-attribute-schema)
[![PHP Version](https://img.shields.io/badge/php-%5E8.3-8892BF.svg?style=flat)](https://packagist.org/packages/jerowork/graphql-attribute-schema)

Build your GraphQL schema for [webonyx/graphql-php](https://github.com/webonyx/graphql-php) using **PHP attributes** instead of array-based configuration.

## Why use this library?

The [webonyx/graphql-php](https://github.com/webonyx/graphql-php) package requires a **schema** to run a GraphQL server. Normally, this schema is defined based on array configuration. 

This package introduces PHP attributes to configure your GraphQL schema instead. By adding attributes (`#[Mutation]`, `#[Query]`, `#[Type]`, etc.) directly to your classes, this library **automatically generates** the GraphQL schema for you.

## 📖 Documentation

The documentation is available on [GitHub pages](https://jerowork.github.io/graphql-attribute-schema/docs) or in the [GitHub repository](https://github.com/jerowork/graphql-attribute-schema/blob/main/docs/index.md).

There is also an example application using most of the features at [https://github.com/jerowork/example-application-graphql-attribute-schema](https://github.com/jerowork/example-application-graphql-attribute-schema).
