# GraphQL Attribute Schema
Build your GraphQL Schema (for [webonyx/graphql-php](https://github.com/webonyx/graphql-php)) based on attributes.

**Note:** this library is still work in progress, and misses some valuable features (see [todo](todo.md))

## Why this library?
[webonyx/graphql-php](https://github.com/webonyx/graphql-php) requires a `Schema` in order to create a GraphQL Server.
This schema configuration is based on (large) PHP arrays.

Wouldn't it be nice to have a library in between which can read your mutation, query and type classes instead, and create
that schema configuration for you?

This is where *GraphQL Attribute Schema* comes into place. By adding attributes to your classes,
*GraphQL Attribute Schema* will create the schema configuration for you.

## Documentation
- [Getting started](getting_started.md)
- [Usage](usage.md)

## A simple example
```php
use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

#[Mutation]
final readonly class CreateUserMutation
{
    public function __invoke(CreateUserInputType $input): User
    {
        // Do your magic; create your user here and return
    }
}

#[Query(description: 'Get a user')]
final readonly class UserQuery
{
    public function __invoke(int $userid): User
    {
        // Do your magic; retrieve your user and return    
    }
}

#[InputType]
final readonly class CreateUserInputType
{
    public function __construct(
        #[Field]
        public int $userId,
        #[Field]
        public string $name,
        #[Field(name: 'phoneNumber')]
        public ?string $phone,
    ) {}
}

#[Type]
final readonly class User
{
    // Define fields by property
    public function __construct(
        #[Field]
        public int $userId,
        #[Field]
        public string $name,   
        public ?string $phone,
        #[Field(description: 'The status of the user')]
        public UserStatusType $status,
    ) {}
    
    // Or define fields by method for additional logic
    #[Field]
    public function getPhoneNumber(): string
    {
        return sprintf('+31%s', $this->phone);
    }
}

#[Enum(description: 'The status of the user')]
enum UserStatusType: string 
{
    case Created = 'CREATED';
    case Removed = 'REMOVED';
}
```

Will result in the following GraphQL schema:
```graphql
type Mutation {
    createUser(input: CreateUserInput!): User!
}

type Query {
    user(userId: Int!): User!
}

type CreateUserInput {
    userId: Int!
    name: String!
    phoneNumber: string
}

type User {
    userId: Int!
    name: String!
    status: UserStatus!
    phoneNumber: string
}

enum UserStatus {
    CREATED
    REMOVED
}
```