# Usage

At minimum, a query and mutation needs to be defined to build a valid schema.

## Attributes

The following attributes can be used:

- [#[Mutation]](#mutation-and-query)
- [#[Query]](#mutation-and-query)
- [#[Type]](#type) (including [Inheritance and interfaces](#inheritance-and-interfaces))
- [#[InputType]](#inputtype)
- [#[Enum]](#enum)
    - [#[EnumValue]](#enum)
- [#[Field]](#field)
- [#[Arg]](#arg)
- [#[Autowire]](#autowire)
- [#[Scalar]](#scalar)
- [#[Cursor]](#cursor) as part of [Connections (Pagination)](#connections-pagination)

See below for more information about each attribute:

### #[Mutation] and #[Query]

Mutations and queries can be defined with `#[Mutation]` and `#[Query]`. In order to configure your class as mutation or
query, just add these attributes on method level:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;

final readonly YourMutation
{
    #[Mutation]
    public function mutationName(SomeInputType $input): OutputType {}
}

final readonly YourQuery
{
    #[Query]
    public function queryName(string $id, int $status) : string {}
}
```

#### Automatic schema creation

*GraphQL Attribute Schema* will read the available method's signature: input arguments and output type. These
will be automatically configured in the schema (this can be overwritten by using `#[Arg]`, see [Arg](#arg) section).

Input and output can be both scalars or objects.
When using objects, make sure these are defined as well with `#[InputType]` for input or `#[Type]` for output.
`#[Enum]` can be used for both input and output.

Also, the name of the mutation or query will be automatically read from the method name (this can be overwritten, see
options).

#### Requirements

Mutations and queries:

- must be in the namespace as defined at `Parser` creation (
  see [Getting started > Integration with webonyx/graphql-php](../docs/getting_started.md#integration-with-webonyxgraphql-php)),
- must be retrievable from the container (`get()`); especially for Symfony users, these should be set to public (e.g.
  with `#[Autoconfigure(public: true)]`).

#### Options

Both `#[Mutation]` and `#[Query]` attribute can be configured:

| Option              | Description                                                                                                                                                                                                                                                                                                                                                                            |
|---------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `name`              | Set custom name of mutation or query (instead of based on method)                                                                                                                                                                                                                                                                                                                      |
| `description`       | Set description of the mutation or query, readable in the GraphQL schema                                                                                                                                                                                                                                                                                                               |
| `type`              | Set custom return type; it can be:<br/>- A Type (FQCN)<br/>- A `ScalarType` (e.g. `ScalarType::Int`)<br/>- A `ListType` (e.g. `new ListType(ScalarType::Int)`)<br/>- A `NullableType` (e.g. `new NullableType(SomeType::class)`)<br/>- A combination of `ListType` and `NullableType` and a Type FQCN or `ScalarType` <br/>(e.g. `new NullableType(new ListType(ScalarType::String))`) |
| `deprecationReason` | If set, deprecates the mutation or query                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                          |

### #[InputType]

Input types can be defined with `#[InputType]`.
In order to configure your class as input type, just add this attribute on class level:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;

#[InputType]
final readonly class YourInputType
{
    public function __construct(
        #[Field]
        public int $id,
        #[Field]
        public ?string $name,
        #[Field]
        public AnotherInputType $anotherInputType,
        #[Field]
        public EnumType $enumType,
    ) {}
}
```

#### Automatic schema creation

*GraphQL Attribute Schema* will read the `__construct` signature: input arguments.
Any input argument with a defined `#[Field]` will be automatically configured in the schema (this can be overwritten,
see [Field](#field) section).

Input can be both scalars or objects.
When using objects, make sure these are defined as well with `#[InputType]` or `#[Enum]`.

Also, the name of the input type will be automatically read from the class name (this can be overwritten, see
options).

#### Options

`#[InputType]` attribute can be configured:

| Option        | Description                                                       |
|---------------|-------------------------------------------------------------------|
| `name`        | Set custom name of input type (instead of based on class)         |
| `description` | Set description of the input type, readable in the GraphQL schema |

### #[Type]

Types can be defined with `#[Type]`.
In order to configure your class as type, just add this attribute on class level:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class YourType
{
    public function __construct(
        #[Field]
        public int $id,
        #[Field]
        public ?string $name,
        #[Field]
        public AnotherType $anotherType,
        #[Field]
        public EnumType $enumType,
    ) {}
    
    #[Field]
    public function getStatus() : EnumStatusType {}
    
    #[Field]
    public function getFoobar(int $status, ?string $baz) : EnumStatusType {}
}
```

#### Inheritance and interfaces
GraphQL supports inheritance with interfaces. In order to configure interfaces, just add `#[Type]` to a PHP interface:
```php
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
interface UserType
{
    // When using PHP 8.4, you can define fields with property hooks
    #[Field]
    public int $id { get; }
    
    // All below PHP 8.4
    #[Field]
    public function getName() : ?string
}
```

Each implementation inherits all fields from the interface, as well as its own fields:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class AgentType implements UserType
{
    public function __construct(
        #[Field]
        public string $status,
        public int $id, // No need to copy #[Field] from interface
    ) {}
    
    // No need to copy #[Field] from interface
    public function getName() : ?string
    {
        return '';
    }
}
```

#### Automatic schema creation

*GraphQL Attribute Schema* will both read the `__construct` signature: input arguments, as well as read all methods.

Any input argument with a defined `#[Field]` will be automatically configured in the schema (this can be overwritten,
see [Field](#field) section).

Any method with a defined `#[Field]` will be automatically configured in the schema (this can be overwritten,
see [Field](#field) section).
The return type is seen as field type, any method input arguments are seen as filter arguments (this can be overwritten
by using `#[Arg]`, see [Arg](#arg) section)..

Input can be both scalars or objects.
When using objects, make sure these are defined as well with `#[InputType]` or `#[Enum]`.

Also, the name of the type will be automatically read from the class name (this can be overwritten, see
options).

#### Options

`#[Type]` attribute can be configured:

| Option        | Description                                                 |
|---------------|-------------------------------------------------------------|
| `name`        | Set custom name of type (instead of based on class)         |
| `description` | Set description of the type, readable in the GraphQL schema |

### #[Enum]

Enums can be defined with `#[Enum]`.
In order to configure your enum class as enum, just add this attribute on class level:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\EnumValue;

#[Enum]
enum YourEnumType: string
{
    case Foo = 'FOO';
    case Bar = 'BAR';
    #[EnumValue(description: 'A description for case Baz')]
    case Baz = 'BAZ';
}
```

#### Automatic schema creation

*GraphQL Attribute Schema* will read the enum signature.

The values for the enum will be automatically read from the PHP `enum`; it uses the string version.

The name of the enum will be automatically read from the class name (this can be overwritten, see options).

#### Requirements

Enums:

- must be of the PHP native `enum` type (no classes with public constants)
- The PHP `enum` type must be a `BackedEnum`

#### Options

`#[Enum]` attribute can be configured:

| Option        | Description                                                 |
|---------------|-------------------------------------------------------------|
| `name`        | Set custom name of enum (instead of based on class)         |
| `description` | Set description of the enum, readable in the GraphQL schema |

Each case in the `enum` type can be configured as well, with the `#[EnumValue]` attribute on case level.

`#[EnumValue]` attribute can be configured:

| Option              | Description                                                      |
|---------------------|------------------------------------------------------------------|
| `description`       | Set description of the enum case, readable in the GraphQL schema |
| `deprecationReason` | If set, deprecates the case                                      |                                                                                                                                                                                                                                                                                                                                                          |

### #[Field]

In `#[Type]` and `#[InputType]`, to define fields, the `#[Field]` attribute can be used.
In order to configure any fields this can be set on constructor property (for `#[InputType]` or `#[Type]`) or
on method (for `#[Type]` only).

The advantage to set on methods for `#[Type]` is that the method can have input arguments as well (e.g. filtering,
injected services).

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[InputType]
final readonly class YourInputType
{
    // Only set on __construct
    public function __construct(
        #[Field]
        public int $id,
        #[Field]
        public ?string $status,
    ) {}
}

#[Type]
final readonly class YourInputType
{
    // Set on __construct
    public function __construct(
        #[Field]
        public int $id,
        #[Field]
        public ?string $status,
    ) {}
    
    // Or set on methods
    #[Field]
    public function getFoobar(): string {}
    
    #[Field]
    public function getBaz(): ?EnumType {}
}
```

#### Options

`#[Field]` attribute can be configured:

| Option              | Description                                                                                                                                                                                                                                                                                                                                                                            |
|---------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `name`              | Set custom name of field (instead of based on class)                                                                                                                                                                                                                                                                                                                                   |
| `description`       | Set description of the field, readable in the GraphQL schema                                                                                                                                                                                                                                                                                                                           |
| `type`              | Set custom return type; it can be:<br/>- A Type (FQCN)<br/>- A `ScalarType` (e.g. `ScalarType::Int`)<br/>- A `ListType` (e.g. `new ListType(ScalarType::Int)`)<br/>- A `NullableType` (e.g. `new NullableType(SomeType::class)`)<br/>- A combination of `ListType` and `NullableType` and a Type FQCN or `ScalarType` <br/>(e.g. `new NullableType(new ListType(ScalarType::String))`) |
| `deprecationReason` | If set, deprecates the field (`#[Type]` only)                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                          |

### #[Arg]

For `#[Mutation]`, `#[Query]` and `#[Type]` methods defined with `#[Field]`, input arguments are read
automatically from the signature.

However, to overwrite e.g. name, `#[Arg]` can be used.

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Mutation]
final readonly class YourMutation
{
    public function __construct(
        public int $id,
        #[Arg(name: 'customName')]
        public ?string $name,
    ) {}
}

#[Query]
final readonly class YourQuery
{
    public function __construct(
        public int $id,
        #[Arg(name: 'customName')]
        public ?string $name,
    ) {}
}

#[Type]
final readonly class YourType
{
    public function __construct(
        ...
    ) {}
    
    public function getFoobar(
        int $filter,
        #[Arg(name: 'customName')]
        ?string $filter2,
    ) {}
}
```

#### Options

`#[Arg]` attribute can be configured:

| Option        | Description                                                                                                                                                                                                                                                                                                                                                                            |
|---------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `name`        | Set custom name of argument (instead of based on class)                                                                                                                                                                                                                                                                                                                                |
| `description` | Set description of the argument, readable in the GraphQL schema                                                                                                                                                                                                                                                                                                                        |
| `type`        | Set custom return type; it can be:<br/>- A Type (FQCN)<br/>- A `ScalarType` (e.g. `ScalarType::Int`)<br/>- A `ListType` (e.g. `new ListType(ScalarType::Int)`)<br/>- A `NullableType` (e.g. `new NullableType(SomeType::class)`)<br/>- A combination of `ListType` and `NullableType` and a Type FQCN or `ScalarType` <br/>(e.g. `new NullableType(new ListType(ScalarType::String))`) |

### #[Autowire]

`#[Type]` objects are typically modeled like DTO's. They are often not defined in any DI container.
Using other services inside a `#[Type]` is therefore not so easy.

This is where `#[Autowire]` comes into play. `#[Type]` methods defined with `#[Field]` can inject services by parameter
by autowiring, with `#[Autowire]`.

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class YourType
{
    public function __construct(
        ...
    ) {}
    
    public function getFoobar(
        int $filter,
        #[Autowire]
        SomeService $service,
    ) {
        // .. use injected $service
    }
}
```

#### Automatic schema creation

Which service to inject, is automatically defined by the type of the parameter.
This can be overwritten by the option `service`, see options section below.

#### Requirements

Autowired services:

- must be retrievable from the container (`get()`); especially for Symfony users, these should be set to public (e.g.
  with `#[Autoconfigure(public: true)]`),

#### Options

| Option    | Description                                                                     |
|-----------|---------------------------------------------------------------------------------|
| `service` | (optional) Set custom service identifier to retrieve from DI Container (PSR-11) |

### #[Scalar]

Webonyx/graphql-php supports 4 native scalar types:

- string
- integer
- boolean
- float

Note: Scalar types can be used for input and output.

You can create your own custom scalar types with the attribute `#[Scalar]`.

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;
use Jerowork\GraphqlAttributeSchema\Type\ScalarType;

#[Scalar]
final readonly class CustomScalar implements ScalarType
{
    public static function serialize(mixed $value): string
    {
        // ...
    }

    public static function deserialize(string $value): mixed
    {
        // ...
    }
}
```

This custom scalar type can then be defined as type with option `type` within other attributes (e.g. `#[Field]`,
`#[Mutation]`).
The `type` option can be omitted when using `alias` in `#[Scalar]`, see options section below.

#### Requirements

Custom scalar types:

- must implement `ScalarType`.

#### Options

| Option        | Description                                                                       |
|---------------|-----------------------------------------------------------------------------------|
| `name`        | Set custom name of scalar type (instead of based on class)                        |
| `description` | Set description of the scalar type, readable in the GraphQL schema                |
| `alias`       | Map scalar type to another class, which removes the need to use the `type` option |

#### Custom ScalarType: DateTimeImmutable

*GraphQL Attribute Schema* already has a custom scalar type built-in: [DateTimeType](../src/Type/DateTimeType.php).

With this custom type, `DateTimeImmutable` can be used out-of-the-box (without any `type` option definition).

When building the `Parser` with the `ParserFactory`, this custom scalar type is already registered.
If not, add `DateTimeType` as a `$customTypes` in the `Parser` construct.

### #[Cursor]

See [Connections (Pagination)](#connections-pagination)

#### Options

`#[Cursor]` attribute can be configured:

| Option | Description                                                                                                                                                                                                                                                                                                |
|--------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `type` | Set custom return type; it can be:<br/>- A custom class implementing `ScalarType` (FQCN)<br/>- A `ScalarType::String`<br/>- A `NullableType` encapsulating any of the previous mentioned types (e.g. `new NullableType(ScalarType::String)`)<br/><br/>*All these types will resolve into a string format.* |

## Connections (Pagination)

*GraphQL Attribute Schema* allows pagination out of the box, following the 'Connection' specification.

More information see:

- https://graphql.org/learn/pagination
- https://relay.dev/graphql/connections.htm

A simple example:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Cursor;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;

final readonly class UsersQuery
{
    #[Query(type: new ConnectionType(User::class))]
    public function getUsers(string $status, EdgeArgs $edgeArgs) : Connection 
    {
        // retrieve a slice of users based on EdgeArgs and custom filters as status
        // ...
    
        return new Connection($users);
    }
}

#[Type]
final readonly class User 
{
    public function __construct(
        #[Field]
        #[Cursor]
        string $id,
        // ... other fields
    ) {}
}
```

With this setup you can query on Users with:

```graphql
query {
    users(status: "active", first: 15) {
        edges {
            cursor
            node {
                id
                # ... other fields
            }
        }
        pageInfo {
            hasNextPage
            hasPreviousPage
            startCursor
            endCursor
        }
    }
}
```

In order to setup a Connection, the Type `ConnectionType` can be used, either as
output type for `#[Query]` and `#[Mutation]` or as output type in `#[Type]` (methods).

When using `ConnectionType`, return type `Connection` is required.
This is a DTO containing a list of entities (nodes) as well as pagination (`hasPreviousPage`, `hasNextPage`)
and slicing parameters (`startCursor`, `endCursor`).

Optionally, as input argument `EdgeArgs` is available, containing input pagination (`first`, `last`)
and slicing arguments (`after`, `before`). Besides `EdgeArgs` it is also possible to add your own input arguments.

Lastly, the 'node' needs to have a `#[Cursor]` defined. This can be a property or method.
It will define the output for each 'edge' cursor.
A `#[Cursor]` parameter does not need to be a `#[Field]` as well, but it is possible to use both attributes for one
parameter.

**Note:** It is possible to omit the `#[Cursor]`, this will result in an always null value when retrieving each 'edge'
cursor.