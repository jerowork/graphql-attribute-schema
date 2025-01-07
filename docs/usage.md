# Usage
At minimum, a query and mutation needs to be defined to build a valid schema.

## Attributes
The following attributes can be used:

- `#[Mutation]`
- `#[Query]`
- `#[InputType]`
- `#[Type]`
- `#[Enum]`
  - `#[EnumValue]`
- `#[Field]`
- `#[Arg]`

See below for more information about each attribute:

### Mutation and query

Mutations and queries can be defined with `#[Mutation]` and `#[Query]`. In order to configure your class as mutation or
query, just add these attributes on class level:

```php
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;

#[Mutation]
final readonly YourMutation
{
    public function __invoke(SomeInputType $input): OutputType {}
}

#[Query]
final readonly YourQuery
{
    public function __invoke(string $id, int $status) : string {}
}
```

#### Automatic schema creation

*GraphQL Attribute Schema* will read the available public method's signature: input arguments and output type. These
will be automatically configured in the schema (this can be overwritten by using `#[Arg]`, see [Arg](#arg) section).

Input and output can be both scalars or objects.
When using objects, make sure these are defined as well with `#[InputType]` for input or `#[Type]` for output.
`#[Enum]` can be used for both input and output.

Also, the name of the mutation or query will be automatically read from the class name (this can be overwritten, see
options).

#### Requirements

Mutations and queries:

- must be in the namespace as defined at `Parser` creation (
  see [Getting started > Integration with webonyx/graphql-php](../docs/getting_started.md#integration-with-webonyxgraphql-php)),
- must be retrievable from the container (`get()`); especially for Symfony users, these should be set to public (e.g.
  with `#[Autoconfigure(public: true)]`),
- must have only one *public* method (apart from `__construct`), which will be called on resolve.

#### Options

Both `#[Mutation]` and `#[Query]` attribute can be configured:

| Option        | Description                                                                                                                                                                                                                                                                                                                                                                            |
|---------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `name`        | Set custom name of mutation or query (instead of based on class)                                                                                                                                                                                                                                                                                                                       |
| `description` | Set description of the mutation or query, readable in the GraphQL schema                                                                                                                                                                                                                                                                                                               |
| `type`        | Set custom return type; it can be:<br/>- A Type (FQCN)<br/>- A `ScalarType` (e.g. `ScalarType::Int`)<br/>- A `ListType` (e.g. `new ListType(ScalarType::Int)`)<br/>- A `NullableType` (e.g. `new NullableType(SomeType::class)`)<br/>- A combination of `ListType` and `NullableType` and a Type FQCN or `ScalarType` <br/>(e.g. `new NullableType(new ListType(ScalarType::String))`) |

### InputType

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

### Type

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

### Enum

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

| Option        | Description                                                      |
|---------------|------------------------------------------------------------------|
| `description` | Set description of the enum case, readable in the GraphQL schema |

### Field

In `#[Type]` and `#[InputType]`, to define fields, the `#[Field]` attribute can be used.
In order to configure any fields this can be set on constructor property (for `#[InputType]` or `#[Type]`) or
on method (for `#[Type]` only).

The advantage to set on methods for `#[Type]` is that the method can have input arguments as well (e.g. filtering).

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

| Option        | Description                                                                                                                                                                                                                                                                                                                                                                            |
|---------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `name`        | Set custom name of field (instead of based on class)                                                                                                                                                                                                                                                                                                                                   |
| `description` | Set description of the field, readable in the GraphQL schema                                                                                                                                                                                                                                                                                                                           |
| `type`        | Set custom return type; it can be:<br/>- A Type (FQCN)<br/>- A `ScalarType` (e.g. `ScalarType::Int`)<br/>- A `ListType` (e.g. `new ListType(ScalarType::Int)`)<br/>- A `NullableType` (e.g. `new NullableType(SomeType::class)`)<br/>- A combination of `ListType` and `NullableType` and a Type FQCN or `ScalarType` <br/>(e.g. `new NullableType(new ListType(ScalarType::String))`) |

### Arg

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
