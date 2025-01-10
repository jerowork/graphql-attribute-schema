<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Parser\NodeParser;

use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Jerowork\GraphqlAttributeSchema\Parser\NodeParser\RetrieveNameForResolverTrait;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Mutation\TestInvalidMutationWithInvalidReturnType;
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

        $class = new ReflectionClass(TestMutation::class);

        self::assertSame('customName', $trait->retrieveNameForResolver(
            $class->getMethod('testMutation'),
            new Mutation('customName'),
        ));
    }

    #[Test]
    public function itShouldRetrieveNameFromMethod(): void
    {
        $trait = new class {
            use RetrieveNameForResolverTrait;
        };

        $class = new ReflectionClass(TestInvalidMutationWithInvalidReturnType::class);

        self::assertSame('mutation', $trait->retrieveNameForResolver(
            $class->getMethod('mutation'),
            new Mutation(),
        ));
    }
}
