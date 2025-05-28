# Usage Guide

- [Attributes](#attributes)
- [Union types](#union-types)
- [Connections (Pagination)](#connections-pagination)
- [Deferred type loading (Solving N+1 problem)](#deferred-type-loading-solving-n1-problem)

📌 At a minimum, you need to define a query and a mutation to build a valid schema.

## Attributes

You can use the following attributes:

- [#[Mutation]](#mutation-and-query)
- [#[Query]](#mutation-and-query)
- [#[Type]](#type)
- [#[InterfaceType]](#interfacetype)
- [#[InputType]](#inputtype)
- [#[Enum]](#enum)
    - [#[EnumValue]](#enum)
- [#[Field]](#field)
- [#[Arg]](#arg)
- [#[Autowire]](#autowire)
- [#[Scalar]](#scalar)
- [#[Cursor]](#cursor)

More details on each attribute are provided below.

### #[Mutation] and #[Query]

You can define mutations and queries using `#[Mutation]` and `#[Query]`.  
Simply add these attributes at the method level:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;

final readonly class YourMutation
{
    #[Mutation]
    public function mutationName(SomeInputType $input): OutputType {}
}

final readonly class YourQuery
{
    #[Query]
    public function queryName(string $id, int $status): string {}
}
```

#### Automatic schema creation

*GraphQL Attribute Schema* automatically reads the method signature, including input arguments and output type.  
These will be configured in the schema without additional setup (though you can override this using `#[Arg]`, see
the [Arg](#arg) section).

Both input and output types can be scalars or objects. When using objects, make sure they're properly defined using
`#[InputType]` for input or `#[Type]` for output. You can also use `#[Enum]` for both input and output.

By default, the mutation or query name is taken from the method name, but you can override it (see options below).

#### Requirements

Mutations and queries must:

- Be within the namespace defined when creating the `Parser` (
  see [Getting Started > Integration with webonyx/graphql-php](../docs/getting_started.md#integration-with-webonyxgraphql-php)).
- Be retrievable from the (PSR-11) container via `get()`.  
  For Symfony users, make sure they're set to public (e.g., with `#[Autoconfigure(public: true)]`).

#### Options

You can configure both `#[Mutation]` and `#[Query]` attributes:

| Option              | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
|---------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `name`              | Custom name for the mutation or query (instead of using the method name).                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `description`       | Description of the mutation or query, visible in the GraphQL schema.                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `type`              | Custom return type, which can be:<br/> - A Type (FQCN)<br/> - A `ScalarType` (e.g., `ScalarType::Int`)<br/> - A `ListType` (e.g., `new ListType(ScalarType::Int)`)<br/> - A `NullableType` (e.g., `new NullableType(SomeType::class)`)<br/> - A combination of `ListType`, `NullableType`, and a Type FQCN or `ScalarType` (e.g., `new NullableType(new ListType(ScalarType::String))`)<br/>- A `UnionType` (see [Union types](#union-types))<br/>- A `ConnectionType` (see [Connections (Pagination)](#connections-pagination)) |
| `deprecationReason` | Marks the mutation or query as deprecated if set.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                |

### #[Type]

You can define types using `#[Type]`. To configure a class as a type, simply add this attribute at the class level:

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
    public function getStatus(): EnumStatusType {}
    
    #[Field]
    public function getFoobar(int $status, ?string $baz): EnumStatusType {}
}
```

#### Automatic schema creation

*GraphQL Attribute Schema* automatically reads the `__construct` signature and detects input arguments,  
as well as method return types.

- Any argument marked with `#[Field]` will be included in the schema by default (you can override this, see
  the [Field](#field) section).
- Any method marked with `#[Field]` will be added to the schema.
  - The return type is considered the field type.
  - Method arguments are treated as filter arguments (you can override this using `#[Arg]`, see [Arg](#arg)).

Like input types, types can be scalars or objects. If you're using objects, ensure they're properly defined with
`#[InputType]` or `#[Enum]`.

By default, the type name is taken from the class name, but you can override it (see options below).

#### Options

You can configure the `#[Type]` attribute:

| Option        | Description                                                 |
|---------------|-------------------------------------------------------------|
| `name`        | Custom name for the type (instead of using the class name). |
| `description` | Description of the type, visible in the GraphQL schema.     |


### #[InterfaceType]

GraphQL supports inheritance using interfaces. To configure an interface, simply add `#[InterfaceType]` to a PHP interface or (abstract) class:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

// Define an InterfaceType on interface
#[InterfaceType]
interface UserType
{
    // With PHP 8.4, you can define fields using property hooks
    #[Field]
    public int $id { get; }
    
    // For PHP versions below 8.4
    #[Field]
    public function getName(): ?string;
}

// Or define an InterfaceType on (abstract) class
#[InterfaceType]
abstract readonly class AbstractUserType
{
    public function __construct(
        #[Field]
        public string $id,
    ) {}

    #[Field]
    abstract public function getName(): ?string;
}
```

Each implementation inherits all fields from the interface, in addition to its own fields:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class AgentType implements UserType
{
    public function __construct(
        #[Field]
        public string $status,
        public int $id, // No need to reapply #[Field] from interface
    ) {}
    
    // No need to reapply #[Field] from interface
    public function getName(): ?string
    {
        return '';
    }
}
```

Other than a single `#[Type]` implementing one interface `#[InterfaceType]` as in the example above, 
*GraphQL Attribute Schema* also supports multiple interfaces:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InterfaceType;

#[InterfaceType]
interface FooType 
{
    #[Field]
    public function getFoo() : string;
}

#[InterfaceType]
interface BarType extends FooType 
{
    #[Field]
    public function getBar() : string;
}

#[InterfaceType]
abstract readonly class AbstractBazType implements FooType 
{
    public function __construct(
        #[Field]
        public string $id,
    ) {}

    #[Field]
    abstract public function getBaz() : string;
}

#[Type]
final readonly class QuxType extends AbstractBazType
{
    // Implement all required logic from interfaces/extends 
}
```
#### Options

You can configure the `#[InterfaceType]` attribute:

| Option        | Description                                                                     |
|---------------|---------------------------------------------------------------------------------|
| `name`        | Custom name for the interface type (instead of using the interface/class name). |
| `description` | Description of the interface type, visible in the GraphQL schema.               |


### #[InputType]

You can define input types using `#[InputType]`. To configure a class as an input type, simply add this attribute at the
class level:

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

*GraphQL Attribute Schema* automatically reads the `__construct` signature and detects input arguments.  
Any argument marked with `#[Field]` will be included in the schema by default (you can override this, see
the [Field](#field) section).

Input values can be scalars or objects. If you're using objects, ensure they're properly defined using `#[InputType]` or
`#[Enum]`.

By default, the input type name is taken from the class name, but you can override it (see options below).

#### Options

You can configure the `#[InputType]` attribute:

| Option        | Description                                                       |
|---------------|-------------------------------------------------------------------|
| `name`        | Custom name for the input type (instead of using the class name). |
| `description` | Description of the input type, visible in the GraphQL schema.     |

### #[Enum]

You can define enums using `#[Enum]`. To configure an enum class, simply add this attribute at the class level:

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

*GraphQL Attribute Schema* automatically detects enum values from the PHP `enum`,  
using the string version of each case.

By default, the enum name is taken from the class name, but you can override it (see options below).

#### Requirements

- Enums must be defined using PHP's native `enum` type (no class-based constants).
- The PHP `enum` must be a `BackedEnum`.

#### Options

You can configure the `#[Enum]` attribute:

| Option        | Description                                                 |
|---------------|-------------------------------------------------------------|
| `name`        | Custom name for the enum (instead of using the class name). |
| `description` | Description of the enum, visible in the GraphQL schema.     |

Each enum case can also be configured using `#[EnumValue]`:

| Option              | Description                                                  |
|---------------------|--------------------------------------------------------------|
| `description`       | Description of the enum case, visible in the GraphQL schema. |
| `deprecationReason` | Marks the case as deprecated if set.                         |

### #[Field]

The `#[Field]` attribute is used in `#[Type]` and `#[InputType]` to define fields.  
You can apply it to constructor properties (for `#[InputType]` and `#[Type]`) or methods (for `#[Type]` only).

Using `#[Field]` on methods in `#[Type]` allows you to add input arguments (e.g., for filtering or injecting services).

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[InputType]
final readonly class YourInputType
{
    // Applied to constructor properties
    public function __construct(
        #[Field]
        public int $id,
        #[Field]
        public ?string $status,
    ) {}
}

#[Type]
final readonly class YourType
{
    // Applied to constructor properties
    public function __construct(
        #[Field]
        public int $id,
        #[Field]
        public ?string $status,
    ) {}
    
    // Applied to methods
    #[Field]
    public function getFoobar(): string {}
    
    #[Field]
    public function getBaz(): ?EnumType {}
}
```

#### Options

You can configure the `#[Field]` attribute:

| Option              | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
|---------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `name`              | Custom name for the field (instead of using the property/method name).                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| `description`       | Description of the field, visible in the GraphQL schema.                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `type`              | Custom return type, which can be:<br/>- A Type (FQCN)<br/>- A `ScalarType` (e.g., `ScalarType::Int`)<br/>- A `ListType` (e.g., `new ListType(ScalarType::Int)`)<br/>- A `NullableType` (e.g., `new NullableType(SomeType::class)`)<br/>- A combination of `ListType`, `NullableType`, and a Type FQCN or `ScalarType` (e.g., `new NullableType(new ListType(ScalarType::String))`)<br/>- A `UnionType` (see [Union types](#union-types))<br/>- A `ConnectionType` (see [Connections (Pagination)](#connections-pagination)) |
| `deprecationReason` | Marks the field as deprecated (only applicable in `#[Type]`).                                                                                                                                                                                                                                                                                                                                                                                                                                                               |

### #[Arg]

When using `#[Mutation]`, `#[Query]`, or methods in `#[Type]` that are marked with `#[Field]`,  
input arguments are automatically read from the method signature.

However, if you need to customize an argument (e.g., rename it), you can use `#[Arg]`.

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

You can configure the `#[Arg]` attribute:

| Option        | Description                                                                                                                                                                                                                                                                                                                                                                           |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `name`        | Custom name for the argument (instead of using the parameter name).                                                                                                                                                                                                                                                                                                                   |
| `description` | Description of the argument, visible in the GraphQL schema.                                                                                                                                                                                                                                                                                                                           |
| `type`        | Custom argument type, which can be:<br/>- A Type (FQCN)<br/>- A `ScalarType` (e.g., `ScalarType::Int`)<br/>- A `ListType` (e.g., `new ListType(ScalarType::Int)`)<br/>- A `NullableType` (e.g., `new NullableType(SomeType::class)`)<br/>- A combination of `ListType`, `NullableType`, and a Type FQCN or `ScalarType` (e.g., `new NullableType(new ListType(ScalarType::String))`). |

### #[Autowire]

Objects in `#[Type]` are usually structured like DTOs and are often **not** defined in the DI container.  
This can make injecting services into a `#[Type]` challenging.

That's where `#[Autowire]` comes in. You can use it inside `#[Type]` methods (marked with `#[Field]`) to automatically
inject services via parameters.

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
        // Use the injected $service
    }
}
```

#### Automatic service injection

By default, the service to inject is determined by the parameter type. If needed, you can override this using the
`service` option (see below).

#### Requirements

- The service must be retrievable from the DI container (`get()`).
- If you're using Symfony, make sure the service is public (e.g., with `#[Autoconfigure(public: true)]`).

#### Options

| Option    | Description                                                                        |
|-----------|------------------------------------------------------------------------------------|
| `service` | *(Optional)* Custom service identifier to retrieve from the DI container (PSR-11). |

### #[Scalar]

Webonyx/graphql-php comes with four built-in scalar types:

- **string**
- **integer**
- **boolean**
- **float**

💡 **Tip:** Scalar types work for both input and output.

If you need a custom scalar type, you can define your own type using `#[Scalar]` and extending Webonyx's `ScalarType`:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;
use GraphQL\Type\Definition\ScalarType;

#[Scalar]
final class CustomScalar extends ScalarType
{
    public function serialize($value): string
    {
        // ...
    }

    public function parseValue($value): string
    {
        // ...
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null): string
    {
        // ...
    }
}
```

More information about Webonyx's `ScalarType` see: https://webonyx.github.io/graphql-php/type-definitions/scalars/#writing-custom-scalar-types

Once defined, you can use your custom scalar type in attributes like `#[Field]` and `#[Mutation]`.  
If you use the `alias` option in `#[Scalar]`, the `type` option becomes optional (see below).

#### Requirements

Custom scalar types must:

- Extend the `ScalarType` class.

#### Options

| Option        | Description                                                         |
|---------------|---------------------------------------------------------------------|
| `name`        | Custom name for the scalar type (defaults to class name).           |
| `description` | Description of the scalar type, visible in the GraphQL schema.      |
| `alias`       | Maps this scalar to another class, eliminating the need for `type`. |

### Built-in custom scalar: DateTimeImmutable

*GraphQL Attribute Schema* includes a built-in custom scalar: **[DateTimeType](../src/Type/DateTimeType.php)**.  
This allows you to use `DateTimeImmutable` out of the box—no extra `type` definition needed!

If you're building the `Parser` with `ParserFactory`, this type is registered automatically.  
Otherwise, you can manually add `DateTimeType` to the `$customTypes` array in the `Parser` constructor.

### #[Cursor]

See **[Connections (Pagination)](#connections-pagination)** for details.

#### Options

You can configure the `#[Cursor]` attribute:

| Option | Description                                                                                                                                                                                                                                                          |
|--------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `type` | Defines a custom return type. It can be:<br/>- A class implementing `ScalarType` (FQCN)<br/>- `ScalarType::String`<br/>- A `NullableType` wrapping one of the above (e.g., `new NullableType(ScalarType::String)`)<br/><br/>*All cursor values resolve to a string.* |

## Union types
*GraphQL Attribute Schema* allows union types as defined by the GraphQL specification.

> "Union types share similarities with Interface types, but they cannot define any shared fields among the constituent types."

_See https://graphql.org/learn/schema/#union-types_

By default, a GraphQL union type cannot not define any shared fields. Instead, it acts like a group of object types.

Therefore, in contrary to all other types, a *GraphQL Attribute Schema* union type **cannot** be defined by an attribute (as that would be an empty class/interface).

Instead, you can define a union type by (a) a native PHP union return type, or (b) a custom set type `UnionType` in a `#[Query]` or `#[Mutation]`.

With using `UnionType`, it is possible to define a custom name for the type. See below:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Option\UnionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;

final readonly class Query
{
    // Define GraphQL union type by PHP return type only
    #[Query]
    public function getFoobar() : UserType|OtherType
    {
        // todo
    }
    
    // Or define GraphQL union type by type in attribute
    #[Query(type: new UnionType('FoobarUnionType', UserType::class, OtherType::class))]
    public function getFoobar()
    {
        // todo
    }
    
    // Or a combination of the above
    #[Query(type: new UnionType('FoobarUnionType'))]
    public function getFoobar() : UserType|OtherType
    {
        // todo
    }
}
```

## Connections (Pagination)

*GraphQL Attribute Schema* provides **built-in pagination** following
the **Relay Connection** specification.

For more details, check out:

- [GraphQL Pagination](https://graphql.org/learn/pagination)
- [Relay Connections](https://relay.dev/graphql/connections.htm)

### Example

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
        // Retrieve a slice of users based on EdgeArgs and filters like status
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

With this setup, you can query paginated users like this:

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

### How Connections Work

To set up a connection, use `ConnectionType`:

- It can be used as an **output type** for `#[Query]` and `#[Mutation]`.
- It can also be used for **return types** in `#[Type]` methods.

If you use `ConnectionType`, the return type **must** be `Connection`.  
This is a **DTO** that contains a list of entities (nodes) and pagination details:

- `hasPreviousPage`
- `hasNextPage`
- `startCursor`
- `endCursor`

### Handling Pagination Arguments

For pagination input, use **EdgeArgs**:

- `first`, `last`; number of records to fetch.
- `after`, `before`; cursors for slicing.

You can also define additional custom input arguments if needed.

### Cursor Requirement

Each 'node' in a connection must have a `#[Cursor]` attribute. You can add this on a **property** or a **method**. This defines the cursor output for each "edge."

💡 **Note:** If you **don't** define a `#[Cursor]`, the cursor will always be `null` when querying.

## Deferred type loading (Solving N+1 problem)

When working with a nested GraphQL model, the famous N+1 problem can become an issue. 
As a solution, Webonyx/graphql-php has introduced so-called 'Deferred type loading'

For more details, check out:
- [Phabricator: Performance: N+1 Query Problem](https://secure.phabricator.com/book/phabcontrib/article/n_plus_one/)
- [Webonyx: Solving N+1 Problem](https://webonyx.github.io/graphql-php/data-fetching/#solving-n1-problem)

To use 'Deferred type loading' in _GraphQL Attribute Schema_, for each type, a custom `DeferredTypeLoader` needs to be created.
This `DeferredTypeLoader` can then be configured in `#[Query]`, `#[Mutation]` or `#[Field]`.

### Example
Create a custom `DeferredTypeLoader` for GraphQL type `SomeType`:

```php 
use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredTypeLoader;

final readonly class SomeTypeLoader implements DeferredTypeLoader
{
    public function __construct(
        private SomeRepository $repository,
    ) {}

    public function load(array $references) : array
    {
        return array_map(
            fn($item) => new DeferredType($item->getId(), new SomeType($item)),
            $this->repository->findByIds($references),
        );
    }
}
```

Configure `#[Query]` or `#[Mutation]`:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;

final readonly class SomeQuery
{
    #[Query(type: SomeType::class, deferredTypeLoader: SomeTypeLoader::class)]
    public function someQuery(string $id): string
    {
        return $id;    
    }
}

final readonly class SomeMutation
{
    #[Mutation(type: SomeType::class, deferredTypeLoader: SomeTypeLoader::class)]
    public function someMutation(string $id): string
    {
        return $id;    
    }
}
```

Or configure `#[Field]` on property or method:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Type]
final readonly class SomeResponseType
{
    // Configure on property
    public function __construct(
        #[Field(name: 'some', type: SomeType::class, deferredTypeLoader: SomeTypeLoader::class)]
        public string $id,
    ) {}
    
    // Or configure on method
    #[Field(type: SomeType::class, deferredTypeLoader: SomeTypeLoader::class)]
    public function getSome(string $id): string 
    {
        return $id;
    }
}
```

### About `DeferredTypeLoader`

A custom implementation needs to implement `DeferredTypeLoader`. This interface defines one method: `load`.

Input `$references` is a list of type references (identifiers), requested by `#[Query]`, `#[Mutation]` or `#[Field]`.

Output is a list of `DeferredType`, containing the reference of each type and the GraphQL type itself.

Each `DeferredTypeLoader` implementation must be retrievable from the (PSR-11) container via `get()`.
For Symfony users, make sure they're set to public (e.g., with `#[Autoconfigure(public: true)]`).
This allows injection of database repositories and other necessary services.
 
### Configuring in `#[Query]`, `#[Mutation]` or `#[Field]`

By configuring the `deferredTypeLoader` option on `#[Query]`, `#[Mutation]` or `#[Field]`, 
the property or method will be converted to a 'Deferred type loading' field.

Each 'Deferred type loading' field should **only** return the identifier (or 'reference').
It can also be a list of references. This reference(s) will automatically be added to the list `$references` in the configured `DeferredTypeLoader`.

The returning reference should be of type: integer, string, `Stringable`, or a list of the mentioned.

As the actual loading now is handled by the `DeferredTypeLoader`, no loading is required in this 'Deferred type loading' field anymore.

**Note:** In order to configure the GraphQL schema with the correct GraphQL type. Setting the `type` option on `#[Query]`, `#[Mutation]` or `#[Field]` is required.