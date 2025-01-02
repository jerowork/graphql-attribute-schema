<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\RetrieveNameForResolverTrait;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestInvalidMutationWithNoMethods;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestMutation;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;

/**
 * @internal
 */
final class RetrieveNameForResolverTraitTest extends TestCase
{
    #[Test]
    public function itShouldRetrieveNameFromAttribute(): void
    {
        $trait = new class {
            use RetrieveNameForResolverTrait;
        };

        self::assertSame('customName', $trait->retrieveNameForResolver(
            new ReflectionClass(TestMutation::class),
            new Mutation('customName'),
            'Mutation',
        ));
    }

    #[Test]
    public function itShouldRetrieveNameFromClass(): void
    {
        $trait = new class {
            use RetrieveNameForResolverTrait;
        };

        self::assertSame('testInvalidMutationWithNoMethods', $trait->retrieveNameForResolver(
            new ReflectionClass(TestInvalidMutationWithNoMethods::class),
            new Mutation(),
            'Mutation',
        ));
    }

    #[Test]
    public function itShouldRetrieveNameFromClassAndRemoveSuffix(): void
    {
        $trait = new class {
            use RetrieveNameForResolverTrait;
        };

        self::assertSame('test', $trait->retrieveNameForResolver(
            new ReflectionClass(TestMutation::class),
            new Mutation(),
            'Mutation',
        ));
    }
}
