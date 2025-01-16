<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\TypeBuilder;

use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ConnectionTypeReference;
use Jerowork\GraphqlAttributeSchema\Parser\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use PHPUnit\Framework\TestCase;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\GetConnectionArgsTrait;
use PHPUnit\Framework\Attributes\Test;

/**
 * @internal
 */
final class GetConnectionArgsTraitTest extends TestCase
{
    #[Test]
    public function itShouldReturnEmptyIfNotConnectionReference(): void
    {
        $trait = new class {
            use GetConnectionArgsTrait;
        };

        self::assertSame([], $trait->getConnectionArgs(ObjectTypeReference::create(TestType::class)));
    }

    #[Test]
    public function itShouldReturnDefaultConnectionArgs(): void
    {
        $trait = new class {
            use GetConnectionArgsTrait;
        };

        self::assertSame([
            [
                'name' => 'first',
                'type' => Type::int(),
                'description' => 'Connection: return the first # items',
                'defaultValue' => 15,
            ],
            [
                'name' => 'after',
                'type' => Type::string(),
                'description' => 'Connection: return items after cursor',
            ],
            [
                'name' => 'last',
                'type' => Type::int(),
                'description' => 'Connection: return the last # items',
            ],
            [
                'name' => 'before',
                'type' => Type::string(),
                'description' => 'Connection: return items before cursor',
            ],
        ], $trait->getConnectionArgs(ConnectionTypeReference::create(TestType::class, 15)));
    }
}
