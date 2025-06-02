# 📖 Documentation

- [Getting Started](getting_started.md)
- [Usage Guide](usage.md)
- [TODO list](todo.md)

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
