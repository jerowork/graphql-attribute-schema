# GraphQL Attribute Schema

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%5E8.3-8892BF.svg?style=flat)](http://www.php.net)

Easily build your GraphQL schema for [webonyx/graphql-php](https://github.com/webonyx/graphql-php) using **PHP attributes** instead of large configuration arrays.

⚠️ **Note:** This library is still a work in progress. Check out the [TODO list](todo.md) for details.

## Why use this library?

The [webonyx/graphql-php](https://github.com/webonyx/graphql-php) package requires a **schema** to run a GraphQL server. Normally, this schema is defined using large and complex PHP arrays, making it harder to manage and maintain.

Wouldn’t it be great if there was a **simpler, more structured way** to define your schema?

That’s exactly what **GraphQL Attribute Schema** does! 🚀

By adding attributes (`#[Mutation]`, `#[Query]`, `#[Type]`, etc.) directly to your classes, this library **automatically generates** the GraphQL schema for you; **cleaner, faster, and easier to maintain**.

## 📖 Documentation

- [Getting Started](getting_started.md)
- [Usage Guide](usage.md)

## 🔥 A Simple Example

### PHP Code
```php
use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;

final readonly class CreateUserMutation
{
    #[Mutation]
    public function createUser(CreateUserInputType $input): User
    {
        // Business logic to create a user
    }
}

final readonly class UserQuery
{
    #[Query(description: 'Get a user')]
    public function user(int $userid): User
    {
        // Fetch and return user data
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
    // Define fields as class properties
    public function __construct(
        #[Field] 
        public int $userId,
        #[Field] 
        public string $name,   
        public ?string $phone,
        #[Field(description: 'The status of the user')] 
        public UserStatusType $status,
    ) {}

    // Define fields with methods for additional logic
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

### 📝 Generated GraphQL Schema

```graphql
type Mutation {
    createUser(input: CreateUserInput!): User!
}

type Query {
    user(userId: Int!): User!
}

input CreateUserInput {
    userId: Int!
    name: String!
    phoneNumber: String
}

type User {
    userId: Int!
    name: String!
    status: UserStatus!
    phoneNumber: String
}

enum UserStatus {
    CREATED
    REMOVED
}
```

### 🚀 Key Benefits

✅ **No more complex PHP arrays** – Define everything using attributes.  
✅ **Cleaner and more maintainable** – Your schema lives in your code, where it belongs.  
✅ **Less boilerplate** – Focus on logic, not configuration.  
✅ **GraphQL schema auto-generated** – No need to manually define types and fields.
